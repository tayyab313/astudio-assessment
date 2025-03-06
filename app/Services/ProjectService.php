<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Project;

class ProjectService
{
    // public function formatProject(Project $project)
    // {
    //     return [
    //         'id' => $project->id,
    //         'name' => $project->name,
    //         'status' => $project->status,
    //         'created_at' => $project->created_at,
    //         'updated_at' => $project->updated_at,
    //         'dynamic_attributes' => $this->getProjectAttributes($project),
    //     ];
    // }

    // private function getProjectAttributes(Project $project)
    // {
    //     return $project->attributeValues->mapWithKeys(function ($attributeValue) {
    //         return [
    //             $attributeValue->attribute->name => [
    //                 'type' => $attributeValue->attribute->type,
    //                 'value' => $attributeValue->value,
    //             ]
    //         ];
    //     });
    // }

    public function saveAttributes(Project $project, array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $attribute = Attribute::firstOrCreate(
                ['name' => $name],
                ['type' => $this->guessAttributeType($value)]
            );

            AttributeValue::updateOrCreate(
                [
                    'attribute_id' => $attribute->id,
                    'entity_type' => Project::class,
                    'entity_id' => $project->id,
                ],
                ['value' => $value]
            );
        }
    }

    private function guessAttributeType($value)
    {
        return is_numeric($value) ? 'number' : (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) ? 'date' : 'text');
    }
}
