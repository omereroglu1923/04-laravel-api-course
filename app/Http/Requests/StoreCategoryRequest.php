<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'photo' => ['nullable', 'file'], // yeni eklenen kural
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name for the category.',
        ];
    }
}
