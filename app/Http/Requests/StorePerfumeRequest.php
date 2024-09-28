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
            'products' => ['required','array'],
            'products.*.name' => ['required','string','max:255'],
            'products.*.price' => ['required','numeric','regex:/^\d{1,8}$/'],
            'products.*.volume' => ['required','integer','max:500'],
            'products.*.slug' => ['required','string',new SlugRule(),'unique:perfumes'],
            'products.*.warranty' => ['string'],
            'products.*.description' => ['string','max:400'],
            'products.*.gender' => ['required','in:male,female,sport'],
            'products.*.percent' => ['nullable','numeric','regex:/^\d{1,4}(\.\d{1,2})?$/',],
            'products.*.amount' => ['nullable','numeric', 'regex:/^\d{1,11}(\.\d{1,2})?$/',],
            'products.*.start_date' => ['nullable','date_format:Y-m-d H:i:s',],
            'products.*.end_date' => ['nullable','date_format:Y-m-d H:i:s',],
            'products.*.discount_card' => ['nullable', 'string','max:255',],
            'products.*.discount_card_percent' => ['nullable','numeric','regex:/^\d{1,4}(\.\d{1,2})?$/',],
            ];
    }

    public function messages()
    {
        return [
            'products.required' => 'ورودی نامعتبر',
            'products.array' => 'ورودی نامعتبر',
            'products.*.name.required' => 'ورودی\' نام \' وارد نشده است ',
            'products.*.price.required' => 'ورودی\' قیمت \' وارد نشده است.',
            'products.*.quantity.required' => 'ورودی\' تعداد \' وارد نشده است',
            'products.*.slug.required' => 'ورودی\' اسلاگ \' وارد نشده است',
            'products.*.gender.required' => 'ورودی\' جنسیت \' وارد نشده است',
        ];
    }
}
