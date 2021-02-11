<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:3'
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Contraseña'
        ];
    }

    public function messages()
    {
        return [
            'email' => 'El campo :attribute no es una dirección de correo electrónico válida',
            'required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute no puede contener menos de :min caracteres'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return ['error' => $validator->errors()->first()];
    }
}
