<?php

namespace App\Http\Requests\Category;

use App\Rules\ValidSlug;
use Illuminate\Support\Str;
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
     * Prepare data for validation (runs before validation rules)
     */
    public function prepareForValidation()
    {
        // Automatically generate a slug from the name if it is not provided in the request
        if(!$this->has('slug') && $this->input('name')){

            $slug = Str::slug($this->input('name'));
            $this->merge([
                'slug' => $slug
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'slug' => ['sometimes', 'string', 'max:255','unique:categories,slug',  new ValidSlug()],
        ];
    }
}
