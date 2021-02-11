<?php

	namespace App\Http\Controllers\Juridico;
	use App\Http\Controllers\Controller;

	use Illuminate\Http\Request;
	use App\Libraries\Cart;
	use Auth;
	use Lang;
	use App\Libraries\IpCheck;

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
	    
	    private $_api_content;

	    public function __construct() {
	    	// if (IpCheck::get() != 'VE' || Cart::count() <= 0) {
	    	// 	return abort(403);
	    	// }
	    	$paypal_conf = \Config::get('paypal');	    	
			$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
			$this->_api_context->setConfig($paypal_conf['settings']);
	    }

	    public function postPayment() {

			$payer = new Payer();
			$payer->setPaymentMethod('paypal');

			$items = [];
			$total = 0;
			$productos = \App('\App\Http\Controllers\Juridico\CarritoController')->getCarrito();
			$currency = 'USD';

			foreach($productos as $producto) {
				$item = new Item();
				$item->setName(\App::getLocale() == 'es' ? $producto['producto']['name'] : $producto['producto']['name_english'])
					 ->setCurrency($currency)
					 ->setDescription(\App::getLocale() == 'es' ? $producto['producto']['name'] : $producto['producto']['name_english'])
					 ->setQuantity($producto['cantidad'])
					 ->setPrice(
					 	round(\App('\App\Http\Controllers\Juridico\CarritoController')->getPrice(
			            	$producto['producto']['price'],
		    				$producto['producto']['coin'],
		    				$producto['cantidad']
			            ),2)
					 );

				$items[] = $item;
				$total += round(\App('\App\Http\Controllers\Juridico\CarritoController')->getPrice(
				            	$producto['producto']['price'],
			    				$producto['producto']['coin'],
			    				$producto['cantidad']
				            ),2) * $producto['cantidad'];
			}

			$item_list = new ItemList();
			$item_list->setItems($items);

			$details = new Details();
			$details->setSubtotal($total)
				->setShipping(0);

			$amount = new Amount();
			$amount->setCurrency($currency)
				->setTotal($total)
				->setDetails($details);

			$transaction = new Transaction();
			$transaction->setAmount($amount)
				->setItemList($item_list)
				->setDescription(Lang::get('Page.PayPal.Compra').': '.Auth::user()->email);

			$redirect_urls = new RedirectUrls();
			$redirect_urls->setReturnUrl(\URL::route('payment.juridico.status'))
				->setCancelUrl(\URL::route('payment.juridico.status'));

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

			\Session::put('paypal_payment_id', $payment->getId());

			if(isset($redirect_url)) {
				return \Redirect::away($redirect_url);
			}

			return \Redirect('carrito')
				->with('errors', Lang::get('Page.PayPal.NoProcesar'));

		}

		public function getPaymentStatus(Request $request) {
			$payment_id = \Session::get('paypal_payment_id');

			\Session::forget('paypal_payment_id');

			$payerId = $request->get('PayerID');
			$token = $request->get('token');

			if (empty($payerId) || empty($token)) {
				return \Redirect('juridico/carrito')
					->with('errors', Lang::get('Page.PayPal.NoProcesar'));
			}

			$payment = Payment::get($payment_id, $this->_api_context);

			$execution = new PaymentExecution();
			$execution->setPayerId($request->get('PayerID'));

			$result = $payment->execute($execution, $this->_api_context);


			if ($result->getState() == 'approved') {
				Cart::destroy();
				\App('\App\Http\Controllers\Juridico\CarritoController')->pay([
					"type" => '2',
					"code" => $payment_id,
					"transaction" => $request->all(),
				]);
				return \Redirect('juridico/carrito')
					->with('success', Lang::get('Page.PayPal.Success'));
			}
			return \Redirect('juridico/carrito')
				->with('errors', Lang::get('Page.PayPal.NoProcesar'));
		}
	}
