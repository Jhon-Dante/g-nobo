<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Pais;
use App\Models\Estado;
use App\Models\Purchase;
use App\Models\Municipality;
use App\Models\Parish;
use Auth;
use Validator;
use Hash;
use Lang;

class PerfilController extends Controller
{

	public function get()
	{
		$user = User::find(Auth::user()->id);

		if (\App::getLocale() == 'es') {
			$paises = Pais::where('id', Pais::COUNTRY_VE)->orderBy('nombre', 'asc')->get()->pluck('nombre', 'id');
		} else {
			$paises = Pais::where('id', Pais::COUNTRY_VE)->orderBy('english', 'asc')->get()->pluck('english', 'id');
		}

		$estados = Estado::where('pais_id', Pais::COUNTRY_VE)->orderBy('nombre', 'asc')->where('status', Estado::STATUS_ACTIVE)->get();
		$municipalities = Municipality::where('status', Municipality::STATUS_ACTIVE)->orderBy('name', 'asc')->get();
		$parishes = Parish::orderBy('name', 'asc')->get();

		return View('perfil.home')->with([
			'user' => $user,
			'paises' => $paises,
			'estados' => $estados,
			'municipalities' => $municipalities,
			'parishes' => $parishes
		]);
	}

	public function pedidos()
	{
		$pedidos = Purchase::with([
			'exchange',
			'details',
			'transfer',
			'delivery',
			'user'
		])
			->where('user_id', Auth::id())
			->whereHas('details', function ($q) {
				$q->whereNotNull('product_amount_id');
			})
			->orderBy('id', 'desc')
			->paginate(5);

		return response()->json([
			'result' => true,
			'pedidos' => $pedidos
		]);
	}

	public function post(Request $request)
	{
		$reglas = [
			'pais_id' => 'required',
			'estado_id' => 'required',
			'municipality_id' => 'required',
			'parish_id' => 'required',
			'referencia' => 'required|max:100',
			'direccion' => 'required|max:100',
			'telefono' => 'required|numeric',
			'codigo' => 'required|numeric',
		];

		$atributos = [
			'pais_id' => Lang::get('Controllers.Atributos.Pais'),
			'estado_id' => Lang::get('Controllers.Atributos.Estado'),
			'municipality_id' => Lang::get('Controllers.Atributos.Municipio'),
			'parish_id' => Lang::get('Controllers.Atributos.Parroquia'),
			'referencia' => Lang::get('Controllers.Atributos.Referencia'),
			'direccion' => Lang::get('Controllers.Atributos.Direccion'),
			'telefono' => Lang::get('Controllers.Atributos.Telefono'),
			'codigo' => Lang::get('Controllers.Atributos.Codigo'),
		];

		$validacion = Validator::make($request->all(), $reglas);
		$validacion->setAttributeNames($atributos);

		if ($validacion->fails()) {
			return response()->json([
				'result' => false,
				'error' => $validacion->messages()->first()
			]);
		} else {
			$user = User::find(Auth::id());
			$user->pais_id = $request->pais_id;
			$user->estado_id = $request->estado_id;
			$user->municipality_id = $request->municipio_id;
			$user->parish_id = $request->parroquia_id;
			$user->referencia = $request->referencia;
			$user->direccion = $request->direccion;
			$user->codigo = $request->codigo;
			$user->telefono = $request->telefono;
			$user->save();

			return response()->json([
				'result' => true
			]);
		}
	}

	public function password(Request $request)
	{
		$reglas = [
			'password' => 'required|confirmed'
		];
		$atributos = [
			'password' => Lang::get('Controllers.Atributos.NewPassword')
		];
		$validacion = Validator::make($request->all(), $reglas);
		$validacion->setAttributeNames($atributos);
		if ($validacion->fails()) {
			return response()->json([
				'result' => false,
				'error' => $validacion->messages()->first()
			]);
		} else {
			$user = User::find(Auth::id());
			if (!Hash::check($request->old_password, $user->password))
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
