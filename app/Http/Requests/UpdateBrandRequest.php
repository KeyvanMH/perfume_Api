<?php

namespace App\Http\Requests;

use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'logo' => ['image', 'mimes:png,jpg,jpeg', 'max:5000'],
            'slug' => [new SlugRule ,'unique:brands'],
            'link' => ['string'],
            'description' => ['string'],
            'title' => ['string'],
            'images.*' => [ 'max:5000','image', 'mimes:webp,jpeg,jpg,png'],
        ];
    }
    public function messages() {
        return [
            'images.*.mime' => ' فرمت عکس پشتیبانی نمی شود!',
            'images.*.uploaded' => ' حجم فایل بیشتر از حد مجاز است!',
            'logo.uploaded' => ' حجم فایل بیشتر از حد مجاز است!',
        ];

    }
}
