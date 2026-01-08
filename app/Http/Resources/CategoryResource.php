<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'sub_categories' => SubCategoryResource::collection($this->whenLoaded('subCategories')),
            'created_at' => $this->created_at,
        ];
    }
}