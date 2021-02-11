<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Lang;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
    }

    public function attributes()
    {
        return [
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
    }

    public function messages()
    {
        return [
            'required_if' => 'El campo :attribute es requerido.',
            'required_unless' => 'El campo :attribute es requerido.'
        ];
    }

    public function formatErrors(Validator $validator)
	{
		return [
            'error' => $validator->errors()->first()
        ];
	}
}
