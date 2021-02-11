<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UsRequest extends FormRequest
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
            'texto' => 'required|min:3',
            'mision' => 'required|min:3',
            'vision' => 'required|min:3',
            'english' => 'required|min:3',
            'mision_english' => 'required|min:3',
            'vision_english' => 'required|min:3'
        ];
    }

    public function attributes()
    {
        return [
            'texto' => 'Quienes somos',
            'mision' => 'Misión',
            'vision' => 'Visión',
            'english' => 'Texto Principal (Inglés)',
            'mision_english' => 'Misión (Inglés)',
            'vision_english' => 'Visión (Inglés)'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute no puede contener menos de :min caracteres'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return ['error' => $validator->errors()->first()];
    }
}
