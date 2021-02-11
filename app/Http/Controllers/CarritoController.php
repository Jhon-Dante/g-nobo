<?php

	namespace App\Http\Controllers;
	
	use App\Models\Setting;
	use Illuminate\Http\Request;
	use App\Libraries\Cart;
	use App\Models\Product;
	use App\Models\Size;
	use App\Models\ProductColor;
	use App\Models\ProductAmount;
	use App\Models\Purchase;
	use App\Models\PurchaseDetails;
	use App\Models\PurchaseDelivery;
	use App\Models\Promotion;
	use App\Models\PromotionUser;
	use App\Models\Offer;
	use App\Models\Category;
	use App\Models\Discount;
	use App\Models\CategorySize;
	use App\Models\ExchangeRate;
	use App\Libraries\IpCheck;
	use App\Mail\StockAlert;
	use MP;
	use Auth;
	use Lang;
	use Mail;
	use App\Models\Social;
	use App\Traits\Payable;
	use Carbon\Carbon;


	

	class CarritoController extends Controller {

		use Payable;

		// public $currency;
		// public $exchange;

		// public function __construct() {
		// 	$this->exchange = ExchangeRate::orderBy('id','desc')->first();

		// 	if (IpCheck::get() != 'VE') {
		// 		$this->currency = '1';
		// 	}
		// 	else {
		// 		$this->currency = '2';
		// 	}
		// }
	    
	    public function get() { 	
	    	$carrito = $this->getCarrito();

            foreach($carrito as $item) {
            	try {
            		if ($item['producto']['amount']['amount'] <= 0) {
	                    Cart::delete($item);
	                }
	                if ($item['cantidad'] > $item['producto']['amount']['amount']) {
	                    $item['cantidad'] = $item['producto']['amount']['amount'];
	                    Cart::set($item);
	                }
            	}
            	catch(\Exception $e) {
            		Cart::delete($item);
            	}                
            }
	    	return View('carrito.home');
	    }	    	

	    public function ajax(Request $request) {
			$carrito = $this->getCarrito();
	    	return response()->json([
	    		'result' => true,
	    		'carrito' => $carrito
	    	]);
	    }

			/**
			* @author smedina
			*
			* configuration initial for cart
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\JsonResponse
			*/
				    public function getConfigurationInicialCart(Request $request)
				    {
				    	try {
				    		$change = ExchangeRate::orderBy('id','desc')->first()->change;
			$minimumDiscount = $this->getMinimumDiscount(); // Trait Payable
			$quantityDiscount = $this->getQuantityDiscount(); // Trait Payable

				    		return response()->json([
				    			'minimunPurchase' => Setting::getMinimunPurchase()->value,
				    			'exchangeRate' => $change,
				    			'minimumDiscount' => $minimumDiscount,
				    			'quantityDiscount' => $quantityDiscount
				    			]);

				    	} catch (\Exception $e) {

				    		return response()->json([
				    			'message' => 'Error, intente de nuevo'
				    			], 500);
				    	}
				    }

	    public function getCarrito() {
	    	$carrito = Cart::get();
	    	for($n = 0; $n < count($carrito); $n++) {

	    		$producto = Product::with(['designs','collections','images','taxe','categories' => function($q) {
			    		$q->with(['sizes']);
			    	},'colors'])->where('status','1')->where('id',$carrito[$n]['id'])->first();

	    		if ($producto != null) {
					$carrito[$n]['producto'] = $producto;
					$carrito[$n]['image'] = $producto->image_url;
					$carrito[$n]['amount'] = ProductAmount::find($carrito[$n]['amount_id']);
					$discount = $producto['discount'];
					$amountId = $carrito[$n]['amount_id'];
					$carrito[$n]['discountAvialable'] = $this->discountAvialable($amountId, $discount);

					if (!isset($carrito[$n]['producto'])) {
						Cart::delete($carrito[$n]);
	    				array_splice($carrito,$n,1);
					}
	    		}
	    		else {
	    			Cart::delete($carrito[$n]);
	    			array_splice($carrito,$n,1);
	    		}
	    		
	    	}
	    	return $carrito;
	    }

	    public function pay($data) {
			$carrito = $data['items'];
			$minimumDiscount = $this->getMinimumDiscount(); // Trait Payable
			$quantityPurchaseDiscount = $this->getQuantityDiscount(); // Trait Payable

			$subtotalWithoutDiscount = 0;
			$subtotalWithDiscount = 0;
			$utilityWithoutDiscount = 0;
			$utility = 0;
			$promotionsUsedIds = [];

			$compra = new Purchase;
	    		$compra->payment_type = $data['type'];
	    		$compra->user_id = Auth::id();
	    		if (isset($data['code']))
	    			$compra->transaction_code = $data['code'];
	    		if (isset($data['transaction']))
	    			$compra->transaction = json_encode($data['transaction']);   		
	    		$compra->exchange_rate_id = $this->exchange->id;
	    		if (isset($data['transfer_id']))
					$compra->transfer_id = $data['transfer_id'];
				if(isset($data['delivery']['free']))
					$compra->free_shipping = $data['delivery']['free'];
				if(isset($data['delivery']['shipping_fee']))
					$compra->shipping_fee = $data['delivery']['shipping_fee'];
				$compra->currency = $data['delivery']['currency'];
			$compra->save();
			
			$delivery = new PurchaseDelivery;
				$delivery->purchase_id = $compra->id;
				$delivery->state_id = $data['delivery']['estado'];
				$delivery->municipality_id = $data['delivery']['municipio'];
				$delivery->parish_id = $data['delivery']['parroquia'];
				$delivery->address = $data['delivery']['address'];
				$delivery->date = new Carbon($data['delivery']['date']);
				$delivery->turn = $data['delivery']['turn'];
				$delivery->type = $data['delivery']['type'];
				$delivery->note = $data['delivery']['note'];
				$delivery->pay_with = $data['delivery']['pay_with'];
			$delivery->save();
			$subtotal = 0;

			$productStockAlert = collect();

	    	foreach($carrito as $item) {
				$basePrice = $item['amount']['price'];
				$amountId = $item['amount_id'];
				$coin = $item['producto']['coin'];
				$offer = $item['producto']['offer'];
				$discount = $item['producto']['discount'];
				$quantity = $item['cantidad'];
				$price = $this->getPriceWithOffer($basePrice, $offer);
				if(isset($item['producto']['isPromotion'])){
					$quantity = $discount['quantity_product'];
					$price = $basePrice - ($basePrice * ($item['producto']['discount']['percentage'] / 100 ));
				}
				
				$detalle = new PurchaseDetails;
				$detalle->purchase_id = $compra->id;
				$detalle->coin = $coin;
				$detalle->product_amount_id = $item['amount_id'];
				$detalleRestante = null;

				$subtotalWithoutDiscount += $basePrice * $quantity;
				$utilityWithoutDiscount += ($basePrice - $item['amount']['cost']) * $quantity;
				if(!isset($item['producto']['isPromotion']) && $this->applyDiscount($amountId, $quantity, $discount)) { // Trait Payable
					if($quantity > $discount['quantity_product']) { 
						$quantityRest = $quantity - $discount['quantity_product'];
						$detalleRestante = new PurchaseDetails;
						$detalleRestante->purchase_id = $compra->id;
						$detalleRestante->price = $price; // Precio sin descuento
						$detalleRestante->coin = $coin;
						$detalleRestante->offer_description = $this->getDescription($offer); // Trait Payable
						$detalleRestante->quantity = $quantityRest;
						$detalleRestante->product_amount_id = $amountId;
						$detalleRestante->save();
						$subtotal = $subtotal + ($price * $quantityRest);
						$utility += ($price - $item['amount']['cost']) * $quantityRest;
					}

					$price = $this->calOfferOrDiscount($price, $discount['percentage']);
					$quantity = $discount['quantity_product']; 
					$detalle->discount_description = $this->getDescription($discount); // Trait Payable
					$detalle->discount_id = $discount['id'];
				}else if(isset($item['producto']['isPromotion'])){
					if($item['cantidad'] > $discount['quantity_product']) {
						$quantityRest = $item['cantidad'] - $discount['quantity_product'];
						$detalleRestante = new PurchaseDetails;
						$detalleRestante->purchase_id = $compra->id;
						$detalleRestante->price = $basePrice; // Precio sin descuento
						$detalleRestante->coin = $coin;
						$detalleRestante->offer_description = $this->getDescription($offer); // Trait Payable
						$detalleRestante->quantity = $quantityRest;
						$detalleRestante->product_amount_id = $amountId;
						$detalleRestante->save();
						$subtotal = $subtotal + ($basePrice * $quantityRest);
						$utility += ($basePrice - $item['amount']['cost']) * $quantityRest;
					}
					if(!in_array($item['producto']['promotion_id'], $promotionsUsedIds)) {
						array_push($promotionsUsedIds, $item['producto']['promotion_id']);
					}
				}
				
				$detalle->offer_description = $this->getDescription($offer); // Trait Payable
				$detalle->price = $price;
				$detalle->quantity = $quantity;
				$detalle->promotion_id = isset($item['producto']['isPromotion']) ? $item['producto']['promotion_id'] : null;
	    		$detalle->save();
				
				$subtotal = $subtotal + ($price * $quantity);
				$utility += ($price - $item['amount']['cost']) * $quantity;
				
	    		$amount = ProductAmount::find($item['amount_id']);
	    			$amount->amount = ($amount->amount - $item['cantidad']) < 0 ? 0 : $amount->amount - $item['cantidad'];
				$amount->save();
				
				if($amount->amount <= $amount->umbral || $amount->amount == 0) {
					$name = $item['producto']['name'];
					$amount = $amount->amount;

					if($item['producto']['variable']) {
						$name = $name . ' ' . $item['amount']['presentation'] . Product::UNITS[$item['amount']['unit']];
					}

					$productStockAlert->push([
						'name' => $name,
						'amount' => $amount
					]);
				}
			}

			$subtotalWithDiscount = $subtotal;

			if($minimumDiscount != null && $subtotal >= $minimumDiscount->minimum_purchase) {
				$discountUtility = round(($utility * ($minimumDiscount->percentage / 100)) * -1, 2);
				$utility = $utility + $discountUtility;
				$priceDiscount = round(($subtotal * ($minimumDiscount->percentage / 100)) * -1, 2);
				$subtotalWithDiscount = $subtotalWithDiscount + $priceDiscount;
				$detail = new PurchaseDetails;
				$detail->discount_id = $minimumDiscount->id;
				$detail->purchase_id = $compra->id;
				$detail->price = $priceDiscount;
				$detail->coin = 2;
				$detail->quantity = 1;
				$detail->discount_description = $this->getDescription($minimumDiscount); // Trait Payable
				$detail->save();
			}

			if($quantityPurchaseDiscount != null) {
				$discountUtility = round(($utility * ($quantityPurchaseDiscount->percentage / 100)) * -1, 2);
				$utility = $utility + $discountUtility;
				$priceDiscount = round(($subtotal * ($quantityPurchaseDiscount->percentage / 100)) * -1, 2);
				$subtotalWithDiscount = $subtotalWithDiscount + $priceDiscount;
				$detail = new PurchaseDetails;
				$detail->discount_id = $quantityPurchaseDiscount->id;
				$detail->purchase_id = $compra->id;
				$detail->price = $priceDiscount;
				$detail->coin = 2;
				$detail->quantity = 1;
				$detail->discount_description = $this->getDescription($quantityPurchaseDiscount);// Trait Payable
				$detail->save();
			}

			$compra->subtotal_bruto = $subtotalWithoutDiscount;
			$compra->subtotal = $subtotalWithDiscount;
			$compra->total = $subtotalWithDiscount + $compra->shipping_fee;
			$compra->utilidad = $utility;
			$compra->utilidad_bruta = $utilityWithoutDiscount;
			$compra->save();

			foreach($promotionsUsedIds as $promotionUsedId) {
				$promotionToCheckUse = Promotion::select('id', 'limit')->where('id', $promotionUsedId)
				->withCount(['uses' => function ($queryPromotion) {
					$queryPromotion->where('user_id', Auth::id());
				}])->first();
				if($promotionToCheckUse->limit > $promotionToCheckUse->uses_count) {
					$newPromotionUse = new PromotionUser();
						$newPromotionUse->promotion_id = $promotionToCheckUse->id;
						$newPromotionUse->user_id = Auth::id();
					$newPromotionUse->save();
				}
			}
			
			Cart::destroy();
			
	    	$_sociales = Social::orderBy('id','desc')->first();

			try {
				Mail::send('emails.compra', ['compra' => $compra, 'user' => Auth::user(), 'sociales' => $_sociales], function ($m) {
					$m->to(Auth::user()->email)
					  ->subject(Lang::get('Page.EmailCompra.Title').' | ' . config('app.name'));
				});
	
				Mail::send('emails.compra', ['compra' => $compra, 'user' => Auth::user(), 'sociales' => $_sociales], function ($m) {
					$m->to(env('MAIL_CONTACTO'))
					  ->subject(Lang::get('Page.EmailCompra.Title').' | ' . config('app.name'));
				});	
				
				Mail::to(env('MAIL_CONTACTO'))->send(new StockAlert($productStockAlert));
				
			} catch(\Exception $e) {
				\Log::info($e);
			}
		}

	    public function getPrice($precio_1,$precio_2,$coin,$cantidad) {
	    	$precio = $cantidad >= 12 ? $precio_2 : $precio_1;
	    	$price = $precio;
			if ($coin == '1' && $this->currency == '2') {
				$price = $price / $this->exchange->change;
			}
			else if ($coin == '2' && $this->currency == '1') {
				$price = $price * $this->exchange->change;
			}
	    	return $price;
	    }

	    public function check(Request $request) {
			$carrito = $request->all();
			$currentDay = Carbon::now()->format('Y-m-d');
			if(count($carrito) == 0) {
				return response()->json([
					'result' => false,
					'error' => 'Debe poseer productos en el carrito para continuar'
				]);
			}
	    	foreach($carrito as $item) {
				$product = Product::where('id', $item['id'])->where('status', Product::STATUS_ACTIVE)->first();
				if (!isset($product)) {
					return response()->json([
			    		'result' => false,
						'error' => 'Un producto de los que posee en el carrito ya no esta disponible',
						'deleted' => true,
						'item' => $item['id']
			    	]);
				}else{
					$name = \App::getLocale() == 'es' ? $item['producto']['name'] : $item['producto']['name_english'];
					if(isset($item['producto']['promotion_id'])) {
						$checkPromotion = Promotion::where('id', $item['producto']['promotion_id'])
						->withCount(['uses' => function ($queryPromotion) {
							$queryPromotion->where('user_id', Auth::id());
						}])->first();
						if(!isset($checkPromotion)) {
							return response()->json([
								'result' => false,
								'error' => 'La promocion no existe',
								'deleted' => true,
								'promotion' => $item['producto']['promotion_id']
							]);
						} else {
							if($checkPromotion->status == Promotion::STATUS_INACTIVE) {
								return response()->json([
									'result' => false,
									'error' => "La promoción {$checkPromotion->title} no se encuentra disponible",
									'deleted' => true,
									'promotion' => $item['producto']['promotion_id']
								]);
							}
							if(!($checkPromotion->start_date <= $currentDay && $checkPromotion->end_date >= $currentDay)) {
								return response()->json([
									'result' => false,
									'error' => "La promoción {$checkPromotion->title} no se encuentra disponible",
									'deleted' => true,
									'promotion' => $item['producto']['promotion_id']
								]);
							}
							if($checkPromotion->uses_count >= $checkPromotion->limit) {
								return response()->json([
									'result' => false,
									'error' => "Usted ha excedido el limite de uso de la promoción {$checkPromotion->title}",
									'deleted' => true,
									'promotion' => $item['producto']['promotion_id']
								]);
							}
						}
					}
					if(isset($item['producto']['offer'])) {
						$checkOffer = Offer::where('id', $item['producto']['offer']['id'])->first();
						if(!isset($checkOffer)) {
							return response()->json([
								'result' => false,
								'error' => 'La oferta no existe',
								'deleted' => true,
								'offer' => $item['producto']['offer']['id']
							]);
						} else {
							if($checkOffer->status == Offer::INACTIVE) {
								return response()->json([
									'result' => false,
									'error' => "La oferta por {$name} no se encuentra disponible",
									'deleted' => true,
									'offer' => $item['producto']['offer']['id']
								]);
							}
							if(!($checkOffer->start <= $currentDay && $checkOffer->end >= $currentDay)) {
								return response()->json([
									'result' => false,
									'error' => "La oferta por {$name} no se encuentra disponible",
									'deleted' => true,
									'offer' => $item['producto']['offer']['id']
								]);
							}
						}
					}
					if(isset($item['producto']['discount']) && !isset($item['producto']['isPromotion'])) {
						$checkDiscount = Discount::where('id', $item['producto']['discount']['id'])->first();
						if(!isset($checkDiscount)) {
							return response()->json([
								'result' => false,
								'error' => 'El descuento no existe',
								'deleted' => true,
								'discount' => $item['producto']['discount']['id']
							]);
						} else {
							if($checkDiscount->status == Discount::INACTIVE) {
								return response()->json([
									'result' => false,
									'error' => "El descuento {$checkDiscount->name} no se encuentra disponible",
									'deleted' => true,
									'discount' => $item['producto']['discount']['id']
								]);
							}
							if(!($checkDiscount->start <= $currentDay && $checkDiscount->end >= $currentDay)) {
								return response()->json([
									'result' => false,
									'error' => "El descuento {$checkDiscount->name} no se encuentra disponible",
									'deleted' => true,
									'discount' => $item['producto']['discount']['id']
								]);
							}
						}
					}
					$productAmount = ProductAmount::find($item['amount']['id']);
					if(!isset($productAmount)){
						return response()->json([
							'result' => false,
							'error' => 'Un producto de los que posee en el carrito ya no esta disponible',
							'deleted' => true,
							'item' => $item['id']
						]);
					}else{
						if($item['amount']['unit']) {
							$name = $name . ' ' . $item['amount']['presentation'] . Product::UNITS[$item['amount']['unit']];
						}
						if($productAmount->amount < $item['cantidad']){
							if($productAmount->amount == 0){
								return response()->json([
									'result' => false,
									'error' => 'Ya no hay stock disponible para el producto: '.$name,
									'deleted' => true,
									'item' => $item['id']
								]);
							}else{
								return response()->json([
									'result' => false,
									'error' => 'Para el Producto: '.$name.' solo contamos con un stock disponible de: '. $productAmount->amount
								]);
							}
						}
					}
				}
	    	}

	    	return response()->json([
	    		'result' => true
	    	]);
	    }

	    public function delete(Request $request) {
	    	Cart::delete($request->item);
	    	return response()->json([
	    		'result' => true,
	    		'carrito' => $this->getCarrito()
	    	]);
	    }
	}
