<?php

	namespace App\Http\Controllers\Juridico;
	use App\Http\Controllers\Controller;

	use Illuminate\Http\Request;
	use App\User;
	use App\Models\Pais;
	use App\Models\Estado;
	use App\Models\Purchase;
	use Auth;
	use Validator;
	use Hash;
	use Lang;

	class PerfilController extends Controller {
	    
	    public function get() {
	    	$user = User::find(Auth::user()->id);
	    	
	    	if (\App::getLocale() == 'es')
	    		$paises = Pais::orderBy('nombre','asc')->get()->pluck('nombre','id');
	    	else
	    		$paises = Pais::orderBy('english','asc')->get()->pluck('english','id');

	    	$estados = Estado::orderBy('nombre','asc')->get();
	    	return View('juridico.perfil.home')->with([
	    		'user' => $user,
	    		'paises' => $paises,
	    		'estados' => $estados
	    	]);
	    }

	    public function pedidos() {
	    	$pedidos = Purchase::where('user_id',Auth::id())->with(['exchange','details' => function($q) {
	    		$q->with(['wholesaler']);
	    	},'transfer'])->whereHas('details',function($q) {
	    		$q->whereNotNull('wholesalers_id')->has('wholesaler');
	    	})->orderBy('id','desc')->paginate(5);
	    	return response()->json([
	    		'result' => true,
	    		'pedidos' => $pedidos
	    	]);
	    }

	    public function post(Request $request) {
	    	$reglas = [
	    		'name' => 'required',
	    		'email' => 'required|email|unique:users,email,'.Auth::id(),
	    		'pais_id' => 'required',
	    		'estado_id' => 'required',
	    		'telefono' => 'required',
	    		'codigo' => 'required'
	    	];
	    	$atributos = [
	    		'name' => Lang::get('Controllers.Atributos.Nombre'),
	    		'email' => Lang::get('Controllers.Atributos.Email'),
	    		'pais_id' => Lang::get('Controllers.Atributos.Pais'),
	    		'estado_id' => Lang::get('Controllers.Atributos.Estado'),
	    		'telefono' => Lang::get('Controllers.Atributos.Telefono'),
	    		'codigo' => Lang::get('Controllers.Atributos.Codigo')
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
	    		$user = User::find(Auth::id());
	    			$user->email = $request->email;
	    			$user->name = $request->name;
	    			$user->pais_id = $request->pais_id;
	    			$user->estado_id = $request->estado_id;
	    			$user->codigo = $request->codigo;
	    			$user->telefono = $request->telefono;
	    		$user->save();

	    		return response()->json([
	    			'result' => true
	    		]);
	    	}
	    }

	    public function password(Request $request) {
	    	$reglas = [
	    		'password' => 'required|confirmed'
	    	];
	    	$atributos = [
	    		'password' => Lang::get('Controllers.Atributos.NewPassword')
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
	    		$user = User::find(Auth::id());
	    		if (!Hash::check($request->old_password,$user->password))
	    			return response()->json([
		    			'result' => false,
		    			'error' => Lang::get('Controllers.NoCoincide')
		    		]);
	    			$user->password = Hash::make($request->password);
	    		$user->save();

	    		return response()->json([
	    			'result' => true
	    		]);
	    	}
	    }
	}
