<?php

namespace App\Http\Requests;

use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerfumeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'regex:/^\d{1,8}$/'],
            'volume' => ['required', 'integer', 'max:500'],
            'slug' => ['required', 'string', new SlugRule, 'unique:perfumes'],
            'warranty' => ['string'],
            'description' => ['string', 'max:400'],
            'gender' => ['required', 'in:male,female,sport'],
            'percent' => ['nullable', 'numeric', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
            'amount' => ['nullable', 'numeric', 'regex:/^\d{1,11}(\.\d{1,2})?$/'],
            'start_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'discount_card' => ['nullable', 'string', 'max:255'],
            'discount_card_percent' => ['nullable', 'numeric', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ورودی\' نام \' وارد نشده است ',
            'price.required' => 'ورودی\' قیمت \' وارد نشده است.',
            'quantity.required' => 'ورودی\' تعداد \' وارد نشده است',
            'slug.required' => 'ورودی\' اسلاگ \' وارد نشده است',
            'gender.required' => 'ورودی\' جنسیت \' وارد نشده است',
        ];
    }
}
