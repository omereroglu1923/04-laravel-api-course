<?php

declare(strict_types=1);

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class CreateBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
			'description' => ['required', 'string'],
        ];
    }
}
