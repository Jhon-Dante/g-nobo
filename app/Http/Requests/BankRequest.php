<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class BankRequest extends FormRequest
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
            'bank_id' => 'required',
            'identification' => 'required_unless:bank_id,1',
            'number' => [
                'required_unless:bank_id,1',
                // 'min:20',
                'nullable',
                Rule::unique('bank_accounts')->ignore($this->id)->where(function ($query) {
                    return $query->whereIn('status', ['1', '0']);
                })
            ],
            'type' => 'required_unless:bank_id,1',
            'email' => 'required_if:bank_id,1|email|nullable'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'propietario',
            'bank_id' => 'banco',
            'identification' => 'identificación',
            'number' => 'número',
            'type' => 'tipo',
            'email' => 'correo electrónico'
        ];
    }

    public function messages() 
    {
        return [
            'required_unless' => 'El campo :attribute es requerido',
            'required_if' => 'El campo :attribute es requerido'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        return ['error' => $validator->errors()->first()];
    }
}
