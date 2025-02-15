<?php

namespace App\Http\Requests;

use App\Http\Const\DefaultConst;
use App\Rules\NumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfumeBasedFactorRequest extends FormRequest
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
            'stock' => ['string','max:255',new NumberRule()],
            'is_active' => ['in:true,false'],
        ];
    }
    public function messages() {
        return [
            'is_active.in' => DefaultConst::INVALID_INPUT
        ];
    }
}
