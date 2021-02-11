<?php

	namespace App\Http\Controllers\Juridico;
	use App\Http\Controllers\Controller;

	use Illuminate\Http\Request;
	use Validator;
	use App\Models\Bank;
	use App\Models\BankAccount;
	use Lang;
	use Auth;
	use App\Models\Transfer;
	use Carbon\Carbon;
	use App\Libraries\IpCheck;
	use App\Libraries\Cart;

	class TransferenciaController extends Controller {

		public function __construct() {
	    	// if (IpCheck::get() != 'VE' || Cart::count() <= 0) {
	    	// 	return abort(403);
	    	// }
	    }
	    
	    public function get() {
	    	$productos = \App('\App\Http\Controllers\Juridico\CarritoController')->getCarrito();

	    	$total = 0;

    		foreach($productos as $producto) {
    			$total = $total + (round(\App('\App\Http\Controllers\Juridico\CarritoController')->getPrice(
	            	$producto['producto']['price'],
    				$producto['producto']['coin'],
    				$producto['cantidad']
	            ),2) * $producto['cantidad']);
    		}

	    	$bancos = Bank::orderBy('name','asc')->get()->pluck('name','id');
	    	$cuentas = BankAccount::with(['bank'])->get()->pluck('full_name','id');
	    	return View('juridico.transfers.home')->with([
	    		'bancos' => $bancos,
	    		'cuentas' => $cuentas,
	    		'total' => $total
	    	]);
	    }

	    public function post(Request $request) {
	    	$reglas = [
	    		'number' => 'required|numeric',
	    		'from' => 'required',
	    		'to' => 'required',
	    		'fecha' => 'required|date_format:d-m-Y'
	    	];
	    	$atributos = [
	    		'number' => Lang::get('Page.Transferencia.Number'),
	    		'from' => Lang::get('Page.Transferencia.From'),
	    		'to' => Lang::get('Page.Transferencia.To'),
	    		'fecha' => Lang::get('Page.Transferencia.Fecha')
	    	];
	    	$validacion = Validator::make($request->all(),$reglas);
	    	$validacion->setAttributeNames($atributos);
	    	if ($validacion->fails()) {
	    		return response()->json([
	    			'result' => false,
	    			'error' => $validacion->messages()->first()
	    		]);
	    	}
	    	else {

	    		$productos = \App('\App\Http\Controllers\Juridico\CarritoController')->getCarrito();

	    		$total = 0;
	    		foreach($productos as $producto) {
	    			$total = $total + round(\App('\App\Http\Controllers\Juridico\CarritoController')->getPrice(
		            	$producto['producto']['price'],
	    				$producto['producto']['coin'],
	    				$producto['cantidad']
		            ),2);
	    		}

	    		$transfer = new Transfer;
	    			$transfer->from_bank_id = $request->from;
	    			$transfer->to_bank_id = $request->to;
	    			$transfer->number = $request->number;
	    			$transfer->user_id = Auth::id();
	    			$transfer->amount = $total;
	    			$transfer->date = Carbon::parse($request->fecha);
	    		$transfer->save();

	    		\App('\App\Http\Controllers\Juridico\CarritoController')->pay([
					"type" => '3',
					"transfer_id" => $transfer->id
				]);

	    		return response()->json([
	    			'result' => true,
	    			'url' => \URL('juridico/carrito')
	    		]);
	    	}
	    }
	}
