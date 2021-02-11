<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Libraries\Cart;
	use Auth;
	use Lang;
	use Session;
	use Validator;
	use App\Libraries\IpCheck;
	use App\Models\PurchaseDetails;
	use App\Models\Discount;
	use App\Models\Purchase;
	use App\Models\Promotion;
	use App\Models\PromotionUser;
	use App\Traits\Payable;
	use PayPal\Rest\ApiContext;
	use PayPal\Auth\OAuthTokenCredential;
	use PayPal\Api\Amount;
	use PayPal\Api\Details;
	use PayPal\Api\Item;
	use PayPal\Api\ItemList;
	use PayPal\Api\Payer;
	use PayPal\Api\Payment;
	use PayPal\Api\RedirectUrls;
	use PayPal\Api\ExecutePayment;
	use PayPal\Api\PaymentExecution;
	use PayPal\Api\Transaction;

	class PaypalController extends Controller {

		use Payable;

	    private static $percent_extra = 5.8;
	    private $_api_content;

	    public function __construct() {
			// if (IpCheck::get() != 'VE') {
	    	// 	return abort(403);
	    	// }
	    	$paypal_conf = \Config::get('paypal');	    	
			$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
			$this->_api_context->setConfig($paypal_conf['settings']);
	    }

	    public function postPayment(Request $request) {
	    	// dd($request->all());
			$payer = new Payer();
			$payer->setPaymentMethod('paypal');
			$minimumDiscount = $this->getMinimumDiscount(); // Trait Payable
			$quantityPurchaseDiscount = $this->getQuantityDiscount(); // Trait Payable

			$items = [];
			$subtotalItems = 0;
			$total = 0;
			$shippingFee = Session::get('shipping_fee', 0);
			$productos = $request->session()->get('cart');
			$currency = 'USD';
			$promotionsUsedIds = [];

			foreach($productos as $producto) {
				$itemName = \App::getLocale() == 'es' ? $producto['producto']['name'] : $producto['producto']['name_english'];
				$offer = $producto['producto']['offer'];
				$discount = $producto['producto']['discount'];
				$quantity = $producto['cantidad'];
				$amountId = $producto['amount_id'];

				$basePrice = $producto['amount']['price'];
				$price = $this->getPriceWithOffer($basePrice, $offer);
				if(isset($producto['producto']['isPromotion'])){
					$price = round($basePrice - ($basePrice * ($producto['producto']['discount']['percentage'] / 100 )), 2);
				}

				if(!isset($producto['producto']['isPromotion']) && $this->applyDiscount($amountId, $quantity, $discount)) {

					if($quantity > $discount['quantity_product']) { 
						$quantityRest = $quantity - $discount['quantity_product'];
						$item = new Item();
							$item->setName($itemName)
							->setCurrency($currency)
							->setDescription($itemName)
							->setQuantity($quantityRest)
							->setPrice(round($price, 2));
						$itemPrice = round($price * $quantityRest, 2);
						$subtotalItems += $itemPrice;
						$items[] = $item;
					}

					$price = $basePrice;
					if($offer != null) {
						$price = $this->getPriceWithOffer($basePrice, $offer);
					}
					$quantity = $discount['quantity_product'];
					
				} else if (isset($producto['producto']['isPromotion'])) {
					if($quantity > $discount['quantity_product']) { 
						$quantityRest = $quantity - $discount['quantity_product'];
						$item = new Item();
							$item->setName($itemName)
							->setCurrency($currency)
							->setDescription($itemName)
							->setQuantity($discount['quantity_product'])
							->setPrice(round($price, 2));
						$itemPrice = round($price * $discount['quantity_product'], 2);
						$subtotalItems += $itemPrice;
						$items[] = $item;
						$quantity = $quantityRest;
					}

					$price = $basePrice;
					if($offer != null) {
						$price = $this->getPriceWithOffer($basePrice, $offer);
					}
					if(!in_array($producto['producto']['promotion_id'], $promotionsUsedIds)) {
						array_push($promotionsUsedIds, $producto['producto']['promotion_id']);
					}
				}

				$item = new Item();
					$item->setName($itemName)
					 ->setCurrency($currency)
					 ->setDescription($itemName)
					 ->setQuantity($quantity)
					 ->setPrice(round($price, 2));

				$itemPrice = round($price * $quantity, 2);

				$items[] = $item;
				$subtotalItems += $itemPrice;
			}

			$subtotalPreserved = $subtotalItems;

			if($minimumDiscount != null && $subtotalItems >= $minimumDiscount->minimum_purchase) {
				$priceDiscount = round(($subtotalItems * ($minimumDiscount->percentage / 100)) * -1, 2);
				$item = new Item();
					$item->setName($this->getDescription($minimumDiscount)) // Trait Payable
					->setCurrency($currency)
					->setDescription($this->getDescription($minimumDiscount)) // Trait Payable
					->setQuantity('1')
					->setPrice(round($priceDiscount, 2));
				$items[] = $item;
				$subtotalItems = $subtotalItems + $priceDiscount; // $priceDiscount esta negativo por eso se suma
			}

			if($quantityPurchaseDiscount != null) {
				$priceDiscount = round(($subtotalPreserved * ($quantityPurchaseDiscount->percentage / 100)) * -1, 2);
				$item = new Item();
					$item->setName($this->getDescription($quantityPurchaseDiscount))
					->setCurrency($currency)
					->setDescription($this->getDescription($quantityPurchaseDiscount))
					->setQuantity('1')
					->setPrice(round($priceDiscount, 2));
				$items[] = $item;
				$subtotalItems = $subtotalItems + $priceDiscount; // $priceDiscount esta negativo por eso se suma
			}

			$totalTemp = $subtotalItems + $shippingFee;

			$porcentajeExtra = round($totalTemp * ($this::$percent_extra / 100), 2);

			$itemPorcentaje = new Item();
					$itemPorcentaje->setName('Comisión PayPal')
					 ->setCurrency($currency)
					 ->setDescription('Comisión PayPal')
					 ->setQuantity(1)
					 ->setPrice($porcentajeExtra);

			$items[] = $itemPorcentaje;

			$subtotal = $subtotalItems +  $porcentajeExtra;
			
			$total = $subtotal + $shippingFee;
			
			$item_list = new ItemList();
			$item_list->setItems($items);

			$details = new Details();
			$details->setSubtotal($subtotal)
				->setShipping($shippingFee);

			$amount = new Amount();
			$amount->setCurrency($currency)
				->setTotal($total)
				->setDetails($details);

			$transaction = new Transaction();
			$transaction->setAmount($amount)
				->setItemList($item_list)
				->setDescription(Lang::get('Page.PayPal.Compra').': '.Auth::user()->email);

			$redirect_urls = new RedirectUrls();
			$redirect_urls->setReturnUrl(\URL::route('payment.status'))
				->setCancelUrl(\URL::route('payment.status'));

			$payment = new Payment();
			$payment->setIntent('Sale')
				->setPayer($payer)
				->setRedirectUrls($redirect_urls)
				->setTransactions([$transaction]);

			try {
				$payment->create($this->_api_context);
			} catch (\PayPal\Exception\PPConnectionException $ex) {
				if (\Config::get('app.debug')) {
					echo "Exception: " . $ex->getMessage() . PHP_EOL;
					$err_data = json_decode($ex->getData(), true);
					exit;
				} else {
					die(Lang::get('Page.PayPal.Error'));
				}
			}

			foreach($payment->getLinks() as $link) {
				if($link->getRel() == 'approval_url') {
					$redirect_url = $link->getHref();
					break;
				}
			}

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

			Session::put('paypal_payment_id', $payment->getId());
			if(isset($redirect_url)) {
				return \Redirect::away($redirect_url);
			}

			return \Redirect('verificacion')
				->with('errors', Lang::get('Page.PayPal.NoProcesar'));

		}

		public function getPaymentStatus(Request $request) {
			$payment_id = \Session::get('paypal_payment_id');

			$delivery = [
				'estado' => Session::get('estado'),
				'municipio' => Session::get('municipio'),
				'parroquia' => Session::get('parroquia'),
				'address' => Session::get('address', null),
				'free' => Session::get('free', false),
				'shipping_fee' => Session::get('shipping_fee', 0),
				'date' => Session::get('date'),
				'turn' => Session::get('turn'),
				'currency' => Session::get('currency', '2'),
				'type' => Session::get('type', null),
				'note' => Session::get('note', null),
				'pay_with' => Session::get('pay_with', null)
			];
			$items = $request->session()->get('cart');
			Session::forget('cart');
			Session::forget('paypal_payment_id');
			Session::forget('estado');
			Session::forget('municipio');
			Session::forget('parroquia');
			Session::forget('address');
			Session::forget('free');
			Session::forget('shipping_fee');
			Session::forget('currency');
			Session::forget('type');
			Session::forget('note');
			Session::forget('pay_with');

			$payerId = $request->get('PayerID');
			$token = $request->get('token');

			if (empty($payerId) || empty($token)) {
				return \Redirect('/')
					->with('errors', Lang::get('Page.PayPal.NoProcesar'));
			}

			$payment = Payment::get($payment_id, $this->_api_context);

			$execution = new PaymentExecution();
			$execution->setPayerId($request->get('PayerID'));

			$result = $payment->execute($execution, $this->_api_context);


			if ($result->getState() == 'approved') {
				Cart::destroy();
				\App('\App\Http\Controllers\CarritoController')->pay([
					"type" => '4',
					"code" => $payment_id,
					"transaction" => $request->all(),
					"delivery" => $delivery,
					'items' => $items
				]);
				return \Redirect('/')
					->with([
						'setCart' => true,
						'success' => Lang::get('Page.PayPal.Success')
					]);
			}
			return \Redirect('verificacion')
				->with('errors', Lang::get('Page.PayPal.NoProcesar'));
		}

		public function purchaseData(Request $request) 
		{
			$reglas = [
	    		'estado' => 'required_if:shipping_selected,0',
	    		'municipio' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
	    		'parroquia' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
	    		'date' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
	    		'turn' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
				'address' => 'nullable',
				'shipping_selected' => 'required',
				'type' => 'required_if:shipping_selected,0'
	    	];
	    	$atributos = [
				'direccion' => 'Direccion',
	    		'estado' => 'Estado',
	    		'municipio' => 'Municipio',
	    		'parroquia' => 'Sector',
				'shipping_selected' => 'Tipo de Envio',
				'address' => 'Direccion',
				'type' => 'Tipo de Entrega',
				'date' => 'Fecha de Entrega',
				'turn' => 'Turno'
			];
			
			$messages = [
				'required_if' => 'El campo :attribute es requerido.',
				'required_unless' => 'El campo :attribute es requerido.'
			];

	    	$validacion = Validator::make($request->all(),$reglas, $messages);
	    	$validacion->setAttributeNames($atributos);
	    	if ($validacion->fails()) {
	    		return response()->json([
					'error' => $validacion->messages()->first()
				], 422);
			}
            Session::put('cart', $request->items);
            Session::put('estado', $request->estado);
            Session::put('municipio', $request->municipio);
            Session::put('parroquia', $request->parroquia);
			Session::put('shipping_selected', $request->shipping_selected);
			Session::put('address', $request->address);
			Session::put('free', $request->free);
			Session::put('shipping_fee', $request->shipping_fee);
			Session::put('date', $request->date);
			Session::put('turn', $request->turn);
			Session::put('currency', $request->currency);
			Session::put('type', $request->type);
			Session::put('note', $request->note);
			Session::put('pay_with', $request->pay_with);

			return response()->json([
				'result' => true,
				'url' => url('payment')
	    	]);
			
		}
	}
