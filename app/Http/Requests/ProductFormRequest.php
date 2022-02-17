<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
            'price' => 'required',
            'SKU' => 'required|unique:products'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'price.required' => 'O campo price é obrigatório',
            'SKU.required' => 'O campo SKU é obrigatório',
            'SKU.unique' => 'Esse SKU já está sendo usado, por favor use outro'
        ];
    }
}
