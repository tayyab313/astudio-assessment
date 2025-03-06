<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dynamic_attributes' => $this->formatAttributes(),
        ];
    }
    /**
     * Format project attributes.
     *
     * @return array
     */
    private function formatAttributes()
    {
        return $this->attributeValues->mapWithKeys(function ($attributeValue) {
            return [
                $attributeValue->attribute->name => [
                    'type' => $attributeValue->attribute->type,
                    'value' => $attributeValue->value,
                ]
            ];
        });
    }
}
