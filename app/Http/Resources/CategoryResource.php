<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo, // yeni eklenen alan
            'description' => $this->when($request->is('api/categories*'), function () use ($request) {
                // Sadece liste görünümünde (tam olarak /api/categories) kısaltılmış göster
                if ($request->is('api/categories')) {
                    return str($this->description)->limit(20);
                }

                // Tekil kayıt görünümünde (/api/categories/{id}) tam metni göster
                return $this->description;
            }),
        ];
    }
}
