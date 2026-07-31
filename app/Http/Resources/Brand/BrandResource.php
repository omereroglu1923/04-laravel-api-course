<?php

declare(strict_types=1);

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at, // dateTimeFormat() kaldırıldı, tip hatası veriyordu
            'updated_at' => $this->updated_at,
        ];
    }
}
