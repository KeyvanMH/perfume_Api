<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandImageRequest extends FormRequest
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
            'images' => ['required','array'],
            'images.*' => ['required','max:5000', 'image', 'mimes:png,jpg,jpeg,webp'],
        ];
    }

    public function messages()
    {
        return [
            'images.*.uploaded' => 'حجم فایل بیشتر از حد مجاز می باشد',
            'images.*.mimes' => 'فرمت عکس پشتیبانی نمی شود',
        ];
    }
}
