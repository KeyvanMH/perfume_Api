<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCredentialRequest extends FormRequest
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
            'phone_number' => ['string', 'size:11', new PhoneNumberRule, 'nullable'],
            'first_name' => ['string', 'max:50', 'min:3', 'nullable'],
            'last_name' => ['string', 'max:50', 'min:3', 'nullable'],
            'email' => ['email', 'nullable'],
            'post_number' => ['string', 'nullable', 'max:10', 'min:3'],
            'city' => ['string', 'nullable', 'max:10', 'exists:cities,id'],
        ];
    }
}
