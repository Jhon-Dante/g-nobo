<?php

namespace App\Traits;

use App\Libraries\IpCheck;
use App\Models\ExchangeRate;
use App\Models\Discount;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Carbon\Carbon;
use Auth;
use DB;

trait Payable
{
    public $currency;
    public $exchange;

    public function __construct()
    {
        $this->exchange = ExchangeRate::orderBy('id', 'desc')->first();

        $this->currency = '2';
        // if (IpCheck::get() != 'VE') {
        //     $this->currency = '1';
        // } else {
        //     $this->currency = '2';
        // }
    }

    public function getTotalToPay($products, $shipping_fee = 0)
    {
        $subtotal = 0;

        foreach ($products as $producto) {
            $basePrice =  $producto['amount']['price'];
            $amountId = $producto['amount_id'];
            $coin = $producto['producto']['coin'];
            $offer = $producto['producto']['offer'];
            $discount = $producto['producto']['discount'];
            $quantity = $producto['cantidad'];

            $price = $this->getPrice($basePrice, $coin, $offer);
            if (!isset($producto['producto']['isPromotion']) &&  $this->applyDiscount($amountId, $quantity, $discount)) {
                if ($quantity > $discount['quantity_product']) {
                    $restQuantity = $quantity - $discount['quantity_product'];
                    $subtotal = $subtotal + round($price * $restQuantity, 2); // Estos no aplican al descuento
                }

                $quantity = $discount['quantity_product']; // Quitamos los que no aplicaron al descuento
                $price = $this->calOfferOrDiscount($price, $discount['percentage']); // Nuevo precio de descuento
            }else if(isset($producto['producto']['isPromotion'])){
                $discountPrice = round($this->calOfferOrDiscount($price, $discount['percentage']), 2);
                $sub = $discount['quantity_product'] * $discountPrice;
                if($quantity > $discount['quantity_product']){
                    $sub += ($quantity - $discount['quantity_product']) * $price;
                }
                $subtotal = $subtotal + round($sub, 2);
            }

            if(!isset($producto['producto']['isPromotion']))
                $subtotal = $subtotal + round($price * $quantity, 2);
        }

        $subtotalPreserved = $subtotal;

        $minimumDiscount = $this->getMinimumDiscount();
        $quantityDiscount = $this->getQuantityDiscount();

        if ($minimumDiscount != null && $subtotal >= $minimumDiscount->minimum_purchase) {
            $subtotal = $this->calOfferOrDiscount($subtotal, $minimumDiscount->percentage);
        }

        if ($quantityDiscount != null) {
            $priceDiscount = round(($subtotalPreserved * ($quantityDiscount->percentage / 100)), 2);
            $subtotal = $subtotal - $priceDiscount;
        }

        return round($subtotal + $shipping_fee, 2);
    }

    public function getPrice($basePrice, $coin, $offer)
    {
        $price = $basePrice;
        if ($coin == '1' && $this->currency == '2') {
            $price = $price / $this->exchange->change;
        } else if ($coin == '2' && $this->currency == '1') {
            $price = $price * $this->exchange->change;
        }

        return $this->getPriceWithOffer($price, $offer);
    }

    public function getPriceWithOffer($basePrice, $offer)
    {
        if ($offer != null) {
            return $this->calOfferOrDiscount($basePrice, $offer['percentage']);
        }

        return $basePrice;
    }

    private function calOfferOrDiscount($basePrice, $percentage)
    {
        $offerAmount = ($percentage / 100) * $basePrice;
        return $basePrice - $offerAmount;
    }

    public function getDescription($discount)
    {
        if ($discount == null) {
            return '';
        }

        $name = array_key_exists('name', $discount) ? $discount['name'] : 'Oferta';
        return  $name . ' -' . $discount['percentage'] . '%';
    }

    public function applyDiscount($amountId, $quantity, $discount)
    {
        if ($discount == null || $quantity < $discount['quantity_product']) {
            return false;
        }

        if (Auth::guest()) {
            return true;
        }

        return $this->discountAvialable($amountId, $discount);
    }

    public function discountAvialable($amountId, $discount)
    {
        if ($discount == null) {
            return false;
        }

        if (Auth::guest()) {
            return true;
        }

        $purchaseIds = Auth::user()->pedidos()->pluck('id');
        $discountUse = PurchaseDetails::select('id')
            ->whereIn('purchase_id', $purchaseIds)
            ->where('product_amount_id', $amountId)
            ->where('discount_id', $discount['id'])->get();

        return $discountUse->count() < $discount['limit'];
    }

    private function getQuantityDiscount()
    {
        $quantityPurchaseDiscount = Discount::where('status', 1)
            ->where('start', '<=', Carbon::now()->format('Y-m-d'))
            ->where('type', 'quantity_purchase')
            ->first();
        if(Auth::user() && !is_null(Auth::user()) && !is_null($quantityPurchaseDiscount)){
            $purchaseIds = Auth::user()->pedidos()->pluck('id');
            $purchases = Purchase::whereIn('id', $purchaseIds)
                ->whereBetween('created_at', [
                    Carbon::parse($quantityPurchaseDiscount->start)->format('Y-m-d 00:00:00'),
                    Carbon::parse($quantityPurchaseDiscount->end)->format('Y-m-d 23:59:59')
                ])
                ->with(['details' => function($q){
                    $q->whereNull('product_amount_id');
                }])
                ->get();
            foreach ($purchases as $key => $purchase) {
                if($purchase->details && count($purchase->details) > 0){
                    if(!is_null($quantityPurchaseDiscount) && !is_null($purchase->details[0]['discount_id'])  && $quantityPurchaseDiscount['id'] == $purchase->details[0]['discount_id']){
                        $quantityPurchaseDiscount = null;
                    }
                }
            }
        }
        if (Auth::guest() || is_null($quantityPurchaseDiscount)) {
            return null;
        }


        if (count($purchases) >= $quantityPurchaseDiscount->quantity_purchase) {
            return $quantityPurchaseDiscount;
        }

        return null;
    }

    private function getMinimumDiscount()
    {
        $minimumDiscount = Discount::where('status', 1)
            ->where('start', '<=', date('Y-m-d'))
            ->where('type', 'minimum_purchase')
            ->first();

        if (Auth::guest() || $minimumDiscount == null) {
            return $minimumDiscount;
        }

        $purchaseIds = Auth::user()->pedidos()->pluck('id');
        $minimumDiscountUse = PurchaseDetails::where('discount_id', $minimumDiscount->id)
            ->whereIn('purchase_id', $purchaseIds)
            ->whereBetween(DB::raw('date(created_at)'), [
                $minimumDiscount->start,
                $minimumDiscount->end
            ])
            ->get();

        if ($minimumDiscountUse->count() >= $minimumDiscount->limit) {
            return null;
        }

        return $minimumDiscount;
    }
}
