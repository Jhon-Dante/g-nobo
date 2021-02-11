<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Hash;
	use Mail;
	use Validator;
	use Lang;
	use App\Models\Password;
	use App\User;
	use App\Libraries\PasswordGenerator;
use App\Mail\Restored;

	class ResetController extends Controller {
	    
	    public function get() {
	    	return View('recuperar');
	    }

	    public function send(Request $request) {
	    	$reglas = [
	    		'email' => 'required|email'
	    	];
	    	$atributos = [
	    		'email' => Lang::get('Controllers.Atributos.Email')
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

	    		Password::where('estatus','1')->where('email',$request->email)->update(['estatus' => '0']);

		    	$user = User::where('email',$request->email)->first();

		    	if (count($user) == 0) {
		    		return response()->json([
			    		'result' => false,
			    		'error' => Lang::get('Page.ResetPassword.CorreoRegistrado')
			    	]);
		    	}

		    	$password = new Password;
		    		$password->email = $request->email;
		    		$password->codigo = strtoupper(PasswordGenerator::get());
		    	$password->save();

		    	Mail::send('emails.reset', ['codigo' => $password->codigo], function ($m) use ($user) {
		            $m->to($user->email)
		              ->subject(Lang::get('Page.ResetPassword.Recuperar').' | ' . config('app.name'));
		        });

		    	return response()->json([
		    		'result' => true
		    	]);
	    	}	    	
	    }

	    public function reenviar(Request $request) {
	    	$password = Password::where('email',$request->email)
	    		->where('estatus','1')
	    		->orderBy('id','desc')
	    		->first();
	    	if (count($password) == 0) {
	    		return response()->json([
	    			'result' => false,
	    			'error' => Lang::get('Page.ResetPassword.NoProcesar')
	    		]);
	    	}

	    	Mail::send('emails.reset', ['codigo' => $password->codigo], function ($m) use ($request) {
	            $m->to($request->email)
	              ->subject(Lang::get('Page.ResetPassword.Recuperar').' | ' . config('app.name'));
	        });

			return response()->json([
	    		'result' => true
	    	]);
	    }

	    public function recuperar(Request $request) {
	    	$reglas = [
	    		'password' => 'required|confirmed'
	    	];
	    	$atributos = [
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
	    		$password = Password::where('codigo',strtoupper($request->codigo))
		    		->where('email',$request->email)
		    		->where('estatus','1')
		    		->count();
		    	if ($password == 0) {
		    		return response()->json([
		    			'result' => false,
		    			'error' => Lang::get('Page.ResetPassword.NoProcesar')
		    		]);
		    	}

		    	$user = User::where('email',$request->email)->first();
		    		$user->password = Hash::make($request->password);
		    	$user->save();

		    	Password::where('estatus','1')->where('email',$request->email)->update(['estatus' => '0']);

				Mail::to($user->email)->send(new Restored($user, $request->password));

	    		return response()->json([
		    		'result' => true
		    	]);
		    }
	    }

	    public function codigo(Request $request) {
	    	$reglas = [
	    		'codigo' => 'required'
	    	];
	    	$atributos = [
	    		'codigo' => Lang::get('Controllers.Atributos.CodigoPassword')
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

	    		$password = Password::where('codigo',strtoupper($request->codigo))
		    		->where('estatus','1')
		    		->orderBy('id','desc')
		    		->first();

		    	if (count($password) == 0) {
		    		return response()->json([
		    			'result' => false,
		    			'error' => Lang::get('Page.ResetPassword.NoCodigo')
		    		]);
		    	}

	    		return response()->json([
		    		'result' => true
		    	]);
		    }
	    }
	}
