<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class DiscountRequest extends FormRequest
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
            'name' => 'required',
            'start' => 'required',
            'end' => 'required',
            'type' => 'required',
            'products_id' => 'required_if:type,quantity_product',
            'minimum_purchase' => 'required_if:type,minimum_purchase',
            'quantity_product' => 'required_if:type,quantity_product',
            'quantity_purchase' => 'required_if:type,quantity_purchase',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'nombre',
            'start' => 'fecha de inicio',
            'end' => 'fecha de finalización',
            'type' => 'tipo',
            'products_id' => 'productos',
            'minimum_purchase' => 'minimo de compra',
            'quantity_product' => 'cantidad de productos',
            'quantity_purchase' => 'cantidad de compras',
        ];
    }

    public function messages()
    {
        return [
            'required_if' => 'El campo :attribute es requerido.',
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [
            'message' => $validator->errors()->first()
        ];
    }
}
