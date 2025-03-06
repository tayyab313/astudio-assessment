<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Attribute;
use App\Models\AttributeValue;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Insert some projects
        $projects = [
            ['name' => 'Project A', 'status' => 'pending'],
            ['name' => 'Project B', 'status' => 'active'],
            ['name' => 'Project C', 'status' => 'completed'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Insert attributes
            $attributes = [
                ['name' => 'priority', 'type' => 'select'],
                ['name' => 'deadline', 'type' => 'date'],
                ['name' => 'budget', 'type' => 'number'],
            ];

            foreach ($attributes as $attributeData) {
                $attribute = Attribute::firstOrCreate($attributeData);

                // Insert attribute values related to the project
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'entity_type' => Project::class,
                    'entity_id' => $project->id,
                    'value' => $this->getAttributeValue($attributeData['name']),
                ]);
            }
        }
    }

    private function getAttributeValue($attributeName)
    {
        return match ($attributeName) {
            'priority' => 'high',
            'deadline' => now()->addDays(rand(10, 30))->toDateString(),
            'budget' => rand(1000, 10000),
            default => null,
        };
    }
}

