<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class SocialRequest extends FormRequest
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
        $contenido = [
            'facebook' => 'required',
            'instagram' => 'required',
            'youtube' => 'required',
            'slogan' => 'required',
            'slogan_english' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email'
        ];
        return $contenido;
    }

    public function attributes() {
        return [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'Youtube',
            'slogan' => 'Slogan',
            'slogan_english' => 'Slogan (Inglés)',
            'address' => 'Dirección',
            'phone' => 'Teléfono',
            'email' => 'Correo'
        ];
    }

    public function messages() {
        return [
            'required' => 'el campo :attribute es requerido',
            'email' => 'el campo :attribute no tiene formato de correo',
            'numeric' => 'el campo :attribute debe ser solamente numérico'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
