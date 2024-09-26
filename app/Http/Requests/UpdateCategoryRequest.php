<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
use App\Rules\SlugRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => ['string','max:30'],
            'type' => [new CategoryRule()],
            'description' => ['string','max:255'],
            'slug' => ['unique:categories',new SlugRule()],

        ];
    }
}
