<?php

namespace App\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
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
            'category_id' => ['sometimes', 'exists:categories,id'],
            'title'       => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:5000'],
            'price'       => ['sometimes', 'integer', 'min:0'],
            'status'      => ['sometimes', 'in:pending,active,rejected'],
            'images'      => ['sometimes', 'array'],
            'images.*'    => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],

        ];
    }
}
