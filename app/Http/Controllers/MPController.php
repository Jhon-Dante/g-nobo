<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use MP;
	use Lang;
	use App\Libraries\IpCheck;
	use App\Libraries\Cart;
	use Illuminate\Support\Facades\Crypt;

	class MPController extends Controller {
	    
	    private $mp;

	    public function __construct() {
	    	// if (IpCheck::get() != 'VE' || Cart::count() <= 0) {
	    	// 	return abort(403);
	    	// }
	    	$this->mp = new MP (env('MP_CLIENT_ID'), env('MP_CLIENT_SECRET'));
	    	$this->mp->sandbox_mode(env('MP_SANDBOX'));
	    }

	    public function create(Request $request) {
	    	
	    	$productos = \App('\App\Http\Controllers\CarritoController')->getCarrito();

	    	$preferenceData = [
	    		"auto_return" => "approved",
	    		"default_payment_method_id" => "credit_card",
	    		"payment_methods" => [
			        "excluded_payment_types" => [
			        	["id" => "atm"],
			            ["id" => "ticket"]
			        ]
			    ],
		    	"back_urls" => [
			      "success" => URL('carrito/response'),
			      "failure" => URL('carrito/response')
			    ]
			];
	    	$currency = 'VES';

	    	foreach ($productos as $producto) {
	    		 $preferenceData['items'][] = [
					"title" => \App::getLocale() == 'es' ? $producto['producto']['name'] : $producto['producto']['name_english'],
		            "quantity" => $producto['cantidad'],
		            "currency_id" => $currency,
		            "unit_price" => round(\App('\App\Http\Controllers\CarritoController')->getPrice(
		            	$producto['producto']['price_1'],
	    				$producto['producto']['price_2'],
	    				$producto['producto']['coin'],
	    				$producto['cantidad']
		            ),2)
	    		 ];
	    	}

	    	$preference = $this->mp->create_preference($preferenceData);

	    	\Session::put('mercadopago_payment_id', $preference['response']['id']);

	    	return Redirect($preference['response'][env('MP_MODE')]);
	    }

	    public function response(Request $request) {
			$payment_id = \Session::get('mercadopago_payment_id');

			\Session::forget('mercadopago_payment_id');

			// if ($payment_id == $request->preference_id) {
				if ($request->collection_status == 'approved') {
					Cart::destroy();
					\App('\App\Http\Controllers\CarritoController')->pay([
						"type" => '1',
						"code" => $payment_id,
						"transaction" => $request->all()
					]);
					return Redirect('carrito')->with('success', Lang::get('Page.PayPal.Success'));
				}
				return Redirect('carrito')->with('errors', Lang::get('Page.PayPal.NoProcesar'));
			// }
			// else {
			// 	return abort(403);
			// }
		}


	}
