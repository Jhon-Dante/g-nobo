<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class StoreWholesalerRequest extends FormRequest
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
            'name_english' => 'required',
            'description' => 'required',
            'description_english' => 'required',
            'price' => 'required|min:1',
            'quantity' => 'required|min:1',
            'main' => 'required',
            'coin' => 'required',
            'filter_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre en español',
            'name_english' => 'nombre en ingles',
            'description' => 'descripción en español',
            'description_english' => 'descripción en ingles',
            'price' => 'precio',
            'quantity' => 'cantidad',
            'main' => 'imagen principal',
            'coin' => 'moneda',
            'filter_id' => 'dispobilidad'
        ];
    }

    public function messages()
    {
        return [
            'min' => 'El valor mínimo :attribute es de :min'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
