<?php

	namespace App\Http\Controllers\Juridico;
	use App\Http\Controllers\Controller;

	use Illuminate\Http\Request;
	use App\Libraries\Cart;
	use App\Models\Filter;
	use App\Models\Wholesaler;
	use Validator;
	use Lang;
	use Auth;

	class TiendaController extends Controller {
	    
	    public function get() {
	    	return View('juridico.tienda.home');
	    }

	    public function ver($id) {
			return View('juridico.tienda.ver')->with(['id' => $id]);
	    }

	    public function add(Request $request) {
    		$item = [
    			'cantidad' => $request->cantidad,
    			'id' => $request->id
    		];

    		$producto = Wholesaler::find($request->id);

			if ($request->cantidad > $producto->quantity) {
				return response()->json([
		    		'result' => false,
		    		'error' => Lang::get('Page.Carrito.NoCantidad').$producto->quantity
	    		]);
			}

    		Cart::set($item);		
	    	return response()->json([
	    		'result' => true,
	    		'carrito' => Cart::get()
	    	]);
	    }

	    public function getProducto(Request $request) {
	    	$producto = Wholesaler::with(['images'])->where('status','1')->where('id',$request->id)->first();
			return response()->json([
				'result' => true,
				'producto' => $producto,
				'carrito' => Cart::get()
			]);
	    }

	    public function ajax(Request $request) {
	    	$query = Wholesaler::with(['images']);

	    	if ($request->has('catalogo')) {
	    		$query->where('filter_id',$request->catalogo);
	    	}

	    	$productos = $query->where('status','1')->paginate(8);

	    	$filtros = Filter::orderBy('name','asc')->get();

	    	return response()->json([
	    		'result' => true,
	    		'productos' => $productos,
	    		'carrito' => Cart::get(),
	    		'filtros' => $filtros
	    	]);
	    }
	}
