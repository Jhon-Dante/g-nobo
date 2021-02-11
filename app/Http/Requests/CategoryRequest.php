<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                Rule::unique('categories')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ],
            'name_english' => [
                'required',
                Rule::unique('categories')->ignore($this->id)->where(function ($query) {
                    return $query->where('status', '1');
                }),
            ],
            'sizes' => "required|array_not_null",
            'subcategories' => 'nullable|array_object_not_null:name,name_english'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre de la categoría',
            'name_english' => 'nombre de la categoría en ingles',
            'sizes' => 'tallas',
            'subcategories' => 'subcategorías'
        ];
    }

    public function messages()
    {
        return [
            'array_object_not_null' => 'Debe completar los campos en :attribute'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
