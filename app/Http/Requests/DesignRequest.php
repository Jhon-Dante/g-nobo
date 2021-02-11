<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class DesignRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('designs')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ],
            'name_english' => [
                'required',
                Rule::unique('designs')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ]
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del diseño',
            'name_english' => 'nombre del diseño en inglés'
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Ya existe un diseño registrado con este nombre',
            'required' => 'El campo :attribute es requerido'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
