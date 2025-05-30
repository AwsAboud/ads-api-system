<?php

namespace App\Http\Requests\Ad;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            // Ensure the price is a number with up to 2 decimal places (e.g. 9, 9.5, 9.99 — but not 9.999)
            'price'       => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'images'      => ['sometimes', 'array'],
            'images.*'    => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048', 'max:50'],
        ];
    }
}
