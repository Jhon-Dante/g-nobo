<?php

namespace App\Http\Controllers;

use App\Traits\Payable;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Transfer;
use Auth;
use Carbon\Carbon;
use Lang;

class StripeController extends Controller
{
    use Payable;

    public function payment(PaymentRequest $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $total = $this->getTotalToPay(
            $request->items,
            $request->shipping_fee
        );

        try {
            $charge = Charge::create([
                "amount" => $total * 100,
                "currency" => "usd",
                "source" => $request->token,
                "description" => "Compra en " . config('app.name')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'error' => $e
            ]);
        }

        $transfer = new Transfer;
        $transfer->name = $request->has('name') ? $request->name : null;
        $transfer->type = Transfer::STRIPE_TYPE;
        $transfer->user_id = Auth::id();
        $transfer->amount = $total;
        $transfer->date = Carbon::now();
        $transfer->number = $charge->id;
        $transfer->save();

        $delivery = [
            'estado' => $request->estado,
            'municipio' => $request->municipio,
            'parroquia' => $request->parroquia,
            'address' => $request->address,
            'shipping_fee' => $request->shipping_fee,
            'type' => $request->type,
            'currency' => $request->currency,
            'date' => $request->date,
            'turn' => $request->turn,
            'type' => $request->type,
            'note' => $request->note,
            'pay_with' => $request->pay_with
        ];

        \App('\App\Http\Controllers\CarritoController')->pay([
            "type" => $request->payment_method,
            "transfer_id" => $transfer->id,
            "delivery" => $delivery,
            "items" => $request->items
        ]);

        \Session::flash('success', Lang::get('Page.PayPal.Success'));

        return response()->json([
            'result' => true,
            'url' => url('/')
        ]);
    }
}
