<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementFormRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'Essa campo é obrigatório',
            'quantity.min' => 'O menor número de produtos que vc pode dar baixa ou adicionar é 1'
        ];
    }
}
