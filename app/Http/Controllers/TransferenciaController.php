<?php

namespace App\Http\Controllers;

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
use App\Traits\Payable;

class TransferenciaController extends Controller
{

	use Payable;

	public function get()
	{
		$total = $this->getTotalToPay(
			\App('\App\Http\Controllers\CarritoController')->getCarrito()
		);

		$bancos = Bank::orderBy('name', 'asc')->get()->pluck('name', 'id');
		$cuentas = BankAccount::with(['bank'])->get()->pluck('full_name', 'id');
		return View('transfers.home')->with([
			'bancos' => $bancos,
			'cuentas' => $cuentas,
			'total' => $total
		]);
	}

	public function post(Request $request)
	{
		$reglas = [
			'number' => 'required|numeric',
			'estado' => 'required_if:shipping_selected,0',
			'municipio' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
			'parroquia' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
			'shipping_selected' => 'required',
			'payment_method' => 'required',
			'address' => 'required',
			'date' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
			'turn' => 'required_if:shipping_selected,0|required_if:shipping_selected,1',
			'name' => 'required_if:payment_method,3',
			'number' => 'required_if:payment_method,1|required_if:payment_method,2|required_if:payment_method,3',
			'type' => 'required_if:shipping_selected,0',
			'bank_id' => 'required_if:payment_method,1|required_if:payment_method,2',
			'pay_with' => 'required_if:payment_method,5'
		];
		$atributos = [
			'number' => Lang::get('Page.Transferencia.Number'),
			'direccion' => 'Direccion',
			'estado' => 'Estado',
			'municipio' => 'Municipio',
			'parroquia' => 'Sector',
			'shipping_selected' => 'Tipo de Envio',
			'payment_method' => 'Metodo de Pago',
			'address' => 'Direccion',
			'name' => 'Nombre',
			'type' => 'Tipo de Entrega',
			'date' => 'Fecha de Entrega',
			'turn' => 'Turno de Entrega',
			'bank_id' => 'Banco',
			'pay_with' => 'Monto de pago'
		];

		$messages = [
			'required_if' => 'El campo :attribute es requerido.',
			'required_unless' => 'El campo :attribute es requerido.'
		];

		$validacion = Validator::make($request->all(), $reglas, $messages);
		$validacion->setAttributeNames($atributos);
		if ($validacion->fails()) {
			return response()->json([
				'error' => $validacion->messages()->first()
			], 422);
		} else {
			$delivery = [
				'estado' => $request->estado,
				'municipio' => $request->municipio,
				'parroquia' => $request->parroquia,
				'address' => $request->address,
				'shipping_fee' => $request->shipping_fee,
				'type' => $request->type,
				'currency' => $request->currency,
				'date' => $request->date,
				'turn' => $request->turn,
				'type' => $request->type,
				'note' => $request->note,
				'pay_with' => $request->pay_with
			];

			$total = $this->getTotalToPay(
				$request->items,
				$request->shipping_fee
			);

			$transfer_id = null;

			if ($request->payment_method != 5) { // EFECTIVO

				$transfer = new Transfer;
				$transfer->name = $request->has('name') ? $request->name : null;
				$transfer->type = $request->payment_method;
				$transfer->number = $request->number;
				$transfer->user_id = Auth::id();
				$transfer->amount = $total;
				$transfer->date = Carbon::now();
				$transfer->bank_id = $request->bank_id;
				$transfer->save();

				$transfer_id = $transfer->id;
			}

			\App('\App\Http\Controllers\CarritoController')->pay([
				"type" => $request->payment_method,
				"transfer_id" => $transfer_id,
				"delivery" => $delivery,
				"items" => $request->items
			]);

			\Session::flash('success', Lang::get('Page.PayPal.Success'));
			
			return response()->json([
				'result' => true,
				'url' => url('/')
			]);
		}
	}
}
