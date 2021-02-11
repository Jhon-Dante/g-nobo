<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class AllyRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('aliados')->ignore($this->id)->where(function ($query) {
                    return $query->where('deleted_at', NULL);
                }),
            ],
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'instagram' => 'nullable|url',
            'address' => 'required|min:3'
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $contenido['image'.$i] = 'mimes:jpg,png,jpeg,gif';
        }
        return $contenido;
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre del Aliado',
            'facebook' => 'facebook',
            'twitter' => 'twitter',
            'instagram' => 'instagram',
            'address' => 'Dirección'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute no puede contener menos de :min caracteres',
            'unique' => 'Ya existe un aliado registrado con este nombre',
            'mimes' => 'La imágen tiene un formato incorrecto'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
