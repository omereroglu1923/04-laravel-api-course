<?php

declare(strict_types=1);

namespace App\Http\Requests\Brand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
			'description' => ['sometimes', 'string'],
        ];
    }
}
