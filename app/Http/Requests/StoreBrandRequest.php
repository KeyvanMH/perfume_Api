<?php

namespace App\Http\Requests;

use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
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
            'logo' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:5000'],
            'slug' => ['required', 'unique:brands', new SlugRule],
            'link' => ['string'],
            'description' => ['required', 'string'],
            'title' => ['required', 'string'],
            'images.*' => [ 'max:5000','image', 'mimes:webp,jpeg,jpg,png'],
        ];
    }

    public function messages()
    {
        return [
            'images.*.image' => ' فرمت عکس پشتیبانی نمی شود!' ,
            'images.*.uploaded' => ' حجم فایل بیشتر از حد مجاز می باشد' ,
            'logo.uploaded' => ' حجم فایل بیشتر از حد مجاز است!' ,
        ];
    }

}
