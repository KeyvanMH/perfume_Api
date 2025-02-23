<?php

namespace App\Http\Requests;

use App\Rules\ProductTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required','string'],
            'product_type' => ['required','string',new ProductTypeRule()],
            'product_quantity' => ['required','integer','min:1','max:50'],
        ];
    }
}
