<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PromotionRequest extends FormRequest
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
            'title' => 'required',
            'image' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'limit' => 'required',
            'products' => 'required'
        ];
    }


    public function attributes()
    {
        return [
            'title' => 'titulo',
            'image' => 'imagen',
            'limit' => 'usos',
            'start_date' => 'fecha de inicio',
            'end_end' => 'fecha de finalización',
            'products' => 'productos',
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
