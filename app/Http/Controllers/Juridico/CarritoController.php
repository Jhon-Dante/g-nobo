<?php

	namespace App\Http\Controllers\Juridico;
	use App\Http\Controllers\Controller;

	use Illuminate\Http\Request;
	use App\Libraries\Cart;
	use App\Models\Purchase;
	use App\Models\PurchaseDetails;
	use App\Models\ExchangeRate;
	use App\Libraries\IpCheck;
	use App\Models\Wholesaler;
	use MP;
	use Auth;
	use Lang;
	use Mail;
	use App\Models\Social;

	class CarritoController extends Controller {

		public $currency;
		public $exchange;

		public function __construct() {
			$this->exchange = ExchangeRate::orderBy('id','desc')->first();

			if (IpCheck::get() != 'VE') {
				$this->currency = '1';
			}
			else {
				$this->currency = '2';
			}
		}
	    
	    public function get() { 	
	    	$carrito = $this->getCarrito();

            foreach($carrito as $item) {
            	try {
            		if ($item['producto']['quantity'] <= 0) {
	                    Cart::delete($item);
	                }
	                if ($item['cantidad'] > $item['producto']['quantity']) {
	                    $item['cantidad'] = $item['producto']['quantity'];
	                    Cart::set($item);
	                }
            	}
            	catch(\Excepcion $e) {
            		Cart::delete($item);
            	}                
            }
	    	return View('juridico.carrito.home');
	    }	    	

	    public function ajax(Request $request) {
	    	$carrito = $this->getCarrito();
	    	return response()->json([
	    		'result' => true,
	    		'carrito' => $carrito
	    	]);
	    }

	    public function getCarrito() {
	    	$carrito = Cart::get();
	    	for($n = 0; $n < count($carrito); $n++) {

	    		$producto = Wholesaler::with(['images'])->where('status','1')->where('id',$carrito[$n]['id'])->first();

	    		if (count($producto) > 0) {
					$carrito[$n]['producto'] = $producto;
	    		}
	    		else {
	    			Cart::delete($carrito[$n]);
	    			array_splice($carrito,$n,1);
	    		}
	    		
	    	}
	    	return $carrito;
	    }

	    public function pay($data) {
	    	$carrito = $this->getCarrito();

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
	    	$compra->save();

	    	foreach($carrito as $item) {
	    		$detalle = new PurchaseDetails;
	    			$detalle->purchase_id = $compra->id;
	    			$detalle->price = $item['producto']['price'];
	    			$detalle->coin = $item['producto']['coin'];
	    			$detalle->wholesalers_id = $item['producto']['id'];
	    			$detalle->quantity = $item['cantidad'];
	    		$detalle->save();

	    		$producto = Wholesaler::find($item['producto']['id']);
	    			$producto->quantity = ($producto->quantity - $item['cantidad']) < 0 ? 0 : $producto->quantity - $item['cantidad'];
	    		$producto->save();
	    	}

	    	Cart::destroy();

	    	$_sociales = Social::orderBy('id','desc')->first();

	    	$compra = Purchase::with(['exchange','details','transfer'])
	    		->whereHas('details',function($q) {
	    			$q->whereNotNull('product_amount_id');
	    		})->where('id',$compra->id)->first();

	    	Mail::send('emails.compra', ['compra' => $compra], function ($m) {
	            $m->to(Auth::user()->email)
	              ->subject(Lang::get('Page.EmailCompra.Title').' | ' . config('app.name'));
	        });

	        Mail::send('emails.compra', ['compra' => $compra], function ($m) use ($_sociales) {
	            $m->to($_sociales->email)
	              ->subject(Lang::get('Page.EmailCompra.Title').' | ' . config('app.name'));
	        });
	    }

	    public function getPrice($price,$coin,$cantidad) {
			if ($coin == '1' && $this->currency == '2') {
				$price = $price / $this->exchange->change;
			}
			else if ($coin == '2' && $this->currency == '1') {
				$price = $price * $this->exchange->change;
			}
	    	return $price;
	    }

	    public function check() {
	    	$carrito = Cart::get();
	    	for($n = 0; $n < count($carrito); $n++) {

	    		$producto = Wholesaler::with(['images'])->where('status','1')->where('id',$carrito[$n]['id'])->first();

	    		if ($producto->status != '1')
		    		return response()->json([
			    		'result' => false,
			    		'error' => Lang::get('Page.Carrito.ProductoEliminado',[
			    			'producto' => \App::getLocale() == 'es' ? $producto->name : $producto->name_english
			    		])
			    	]);
	    		
				$carrito[$n]['producto'] = $producto;
	    	}

	    	foreach($carrito as $item) {	    		
	    		if ($item['cantidad'] > $item['producto']['quantity']) {
	    			return response()->json([
			    		'result' => false,
			    		'error' => Lang::get('Page.Carrito.NoCantidadProducto',[
			    			'cantidad' => $item['producto']['quantity'],
			    			'producto' => \App::getLocale() == 'es' ? $item['producto']['name'] : $item['producto']['name_english']
			    		])
			    	]);
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
