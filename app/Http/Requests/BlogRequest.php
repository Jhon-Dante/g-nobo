<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BlogRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::unique('blogs')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ],
            'title_english' => [
                'required',
                Rule::unique('blogs')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ],
            'description' => 'required|min:3',
            'description_english' => 'required|min:3'
        ];
        for ($i = 0; $i < $this->count; $i++) {
            $contenido['image'.$i] = 'mimes:jpg,png,jpeg,gif'; 
        }
        return $contenido;
    }

    public function attributes()
    {
        return [
            'title' => 'Título',
            'title_english' => 'Título (Inglés)',
            'description' => 'Descripción',
            'description_english' => 'Descripción (Inglés)'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido',
            'min' => 'El campo :attribute no puede contener menos de :min caracteres',
            'unique' => 'Ya existe un Blog registrado con este nombre',
            'mimes' => 'La imágen tiene un formato incorrecto'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
