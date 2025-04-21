<?php

namespace App\Http\Requests;

use App\Http\Const\DefaultConst;
use Illuminate\Foundation\Http\FormRequest;

class StorePerfumeImageRequest extends FormRequest
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
            'images.*' => ['required', 'image', DefaultConst::MIME_TYPE, DefaultConst::IMAGE_MAX_SIZE], // todo unified image in defaultConst in all project
        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'عکسی آپلود نشده است',
        ];
    }
}
