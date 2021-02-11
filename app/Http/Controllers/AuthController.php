<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Validator;
	use Auth;
	use Hash;
	use App\Models\Pais;
	use App\Models\Estado;
	use App\Libraries\Cart;
	use App\Mail\Welcome;
	use App\Mail\AdminNewUser;
	use App\User;
	use Illuminate\Support\Facades\Mail;
	use Lang;

	class AuthController extends Controller {
	    
	    public function getLogin() {
	    	return View('auth.login');
	    }

	    public function postLogin(Request $request) {
	    	$reglas = [
	    		'email' => 'required',
	    		'password' => 'required'
	    	];
	    	$atributos = [
	    		'email' => Lang::get('Controllers.Atributos.Email'),
	    		'password' => Lang::get('Controllers.Atributos.Password')
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
	    		$data = [
	    			'email' => $request->email,
	    			'password' => $request->password
	    		];
	    		if (Auth::attempt($data,true)) {
	    			if (Auth::user()->status != 1) {
	    				Auth::logout();
	    				return response()->json([
			    			'result' => false,
			    			'error' => Lang::get('Controllers.NoLogin')
			    		]);
	    			}
	    			if (Auth::user()->type == 2) {
	    				Cart::destroy();
	    			}
	    			if (Auth::user()->nivel == '1') {
						if($request->count > 0) {
							return response()->json([
								'result' => true,
								'url' => \URL('/verificacion')
							]);
						}
	    				return response()->json([
			    			'result' => true,
			    			'url' => \URL('/')
			    		]);
	    			}
	    			else {
						return response()->json([
			    			'result' => true,
			    			'url' => \URL('/admin')
			    		]);
	    			}
	    		}
	    		else
	    			return response()->json([
	    				'result' => false,
	    				'error' => Lang::get('Controllers.Login')
	    			]);
	    	}
	    }

	    public function getRegister() {
	    	if (\App::getLocale() == 'es')
	    		$paises = Pais::orderBy('nombre','asc')->get()->pluck('nombre','id');
	    	else
	    		$paises = Pais::orderBy('english','asc')->get()->pluck('english','id');
	    	
	    	$estados = Estado::orderBy('nombre','asc')->get();
	    	return View('auth.register')->with([
	    		'paises' => $paises,
	    		'estados' => $estados
	    	]);
	    }

	    public function postRegister(Request $request) {
	    	$reglas = [
	    		'name' => 'required',
	    		'type' => 'required',
	    		'identificacion' => 'required|numeric',
				'empresa' => 'required_if:type,juridica',
				'fiscal' => 'required_if:type,juridica',
				'email' => 'required|email|unique:users,email',
	    		'telefono' => 'required|numeric',
	    		'estado' => 'required',
	    		'municipio' => 'required',
	    		'parroquia' => 'required',
	    		'direccion' => 'required',
	    		'password' => 'required|confirmed|min:6'
	    	];
	    	$atributos = [
	    		'name' => Lang::get('Controllers.Atributos.Nombre'),
	    		'email' => Lang::get('Controllers.Atributos.Email'),
	    		'type' => Lang::get('Controllers.Atributos.Tipo'),
	    		'identificacion' => Lang::get('Controllers.Atributos.Identificacion'),
	    		'telefono' => Lang::get('Controllers.Atributos.Telefono'),
	    		'estado' => Lang::get('Controllers.Atributos.Estado'),
	    		'direccion' => Lang::get('Controllers.Atributos.Direccion'),
				'password' => Lang::get('Controllers.Atributos.Password'),
				'fiscal' => 'dirección fiscal',
				'empresa' =>  Lang::get('Controllers.Empresa')
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
	    		$user = new User;
	    			$user->name = $request->name;
	    			$user->email = $request->email;
	    			$user->persona = $request->type == 'juridica' ? USER::JURIDICO : USER::NATURAL; 
	    			$user->identificacion = $request->identificacion;
	    			$user->telefono = $request->telefono;
	    			$user->pais_id = Pais::VENEZUELA_ID;
	    			$user->estado_id = $request->estado;
	    			$user->municipality_id = $request->municipio;
	    			$user->parish_id = $request->parroquia;
	    			$user->codigo = 2103; // Se pode por defecto ya que viveres no lo necesita
	    			$user->direccion = $request->direccion;
	    			$user->password = Hash::make($request->password);
					$user->empresa = $request->empresa;
					$user->fiscal = $request->fiscal;
					$user->referencia = $request->referencia;
				$user->save();

				Mail::to($user->email)->queue(new Welcome($user, $request->password));
				Mail::to(env('MAIL_CONTACTO'))->queue(new AdminNewUser($user));

				Auth::login($user);

				if(Cart::count() > 0) {

					return response()->json([
						'result' => true,
						'url' => \URL('/verificacion')
					]);
				}
				
	    		return response()->json([
	    			'result' => true,
	    			'url' => \URL('/')
	    		]);
	    	}
	    }

	    public function logout() {
	    	if (Auth::check()) {
	    		if (Auth::user()->type == 2) {
		    		Cart::destroy();
		    	}
		    	Auth::logout();
	    	}	    	
	    	return Redirect('/');
	    }
	}
