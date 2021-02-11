<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class TermsRequest extends FormRequest
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
            'terms_text' => 'required',
            'terms_english' => 'required',
            'conditions_text' => 'required',
            'conditions_english' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'terms_text' => 'terminos y condiciones (Español)',
            'terms_english' => 'terminos y condiciones (Ingles)',
            'conditions_text' => 'condiciones de compra (Español)',
            'conditions_english' => 'condiciones de compra (Ingles)'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return [ 'error' => $validator->errors()->first() ];
    }
}
