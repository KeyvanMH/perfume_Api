<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required','string','max:30'],
            'type' => ['required',new CategoryRule()],
            'description' => ['string','max:255'],
            'slug' => ['required','unique:categories',new SlugRule()],
        ];
    }
}
