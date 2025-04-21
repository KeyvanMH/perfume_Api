<?php

namespace App\Http\Requests;

use App\Http\Const\DefaultConst;
use App\Rules\BrandExistenceRule;
use App\Rules\CategoryExistenceRule;
use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerfumeBasedFactorRequest extends FormRequest
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
            'products' => ['required', 'array'],
            'products.*.category' => ['required', 'string', new CategoryExistenceRule, new SlugRule],
            'products.*.brand' => ['required', 'string', new BrandExistenceRule, new SlugRule],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.price' => ['required', 'numeric', 'regex:/^\d{1,8}$/'],
            'products.*.volume' => ['required', 'integer', 'max:200'],
            'products.*.quantity' => ['required', 'integer'],
            'products.*.slug' => ['required', 'string', new SlugRule],
            'products.*.warranty' => ['string', 'max:255'],
            'products.*.description' => ['required', 'string', 'max:255'],
            'products.*.gender' => ['required', 'in:male,female,sport'],
            'products.*.discount_percent' => ['nullable', 'numeric', 'regex:/^\d{1,2}(\.\d{1,2})?$/'],
            'products.*.amount' => ['nullable', 'numeric', 'regex:/^\d{1,11}(\.\d{1,2})?$/'],
            'products.*.start_date' => ['nullable', 'date_format:Y-m-d H:i'],
            'products.*.end_date' => ['nullable', 'date_format:Y-m-d H:i'],
            'products.*.discount_card' => ['nullable', 'string', 'max:255'],
            'products.*.discount_card_percent' => ['nullable', 'numeric', 'regex:/^\d{1,2}(\.\d{1,2})?$/'],
            'products.*.images' => ['array'],
            'products.*.images.*' => ['string'],
        ];
    }

    public function messages()
    {
        return [
            'products.required' => DefaultConst::INVALID_INPUT,
            'products.array' => DefaultConst::INVALID_INPUT,
            'products.*.category.required' => 'ورودی \'دسته بندی\' وارد نشده است ',
            'products.*.brand.required' => 'ورودی \'برند\' وارد نشده است ',
            'products.*.name.required' => 'ورودی \'نام\' وارد نشده است ',
            'products.*.volume.required' => 'ورودی \'حجم\' وارد نشده است ',
            'products.*.price.required' => 'ورودی \'قیمت\' وارد نشده است.',
            'products.*.quantity.required' => 'ورودی \'تعداد\' وارد نشده است',
            'products.*.slug.required' => 'ورودی \'اسلاگ\' وارد نشده است',
            'products.*.gender.required' => 'ورودی \'جنسیت\' وارد نشده است',
        ];
    }
}
