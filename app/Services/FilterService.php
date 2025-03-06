<?php

// app/Services/FilterService.php
namespace App\Services;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;

class FilterService
{
    /**
     * Apply filters to the query
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function filter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            // Check if it's a dynamic attribute or a regular column
            $attribute = Attribute::where('name', $key)->first();

            if ($attribute) {
                // It's a dynamic attribute (EAV)
                $this->applyEavFilter($query, $attribute, $value);
            } else {
                // It's a regular column
                $this->applyRegularFilter($query, $key, $value);
            }
        }

        return $query;
    }

    /**
     * Apply filter for EAV attributes
     *
     * @param Builder $query
     * @param Attribute $attribute
     * @param mixed $value
     * @return void
     */
    private function applyEavFilter(Builder $query, Attribute $attribute, $value): void
{
    [$operator, $filterValue] = $this->parseOperator($value);

    // Ensure numeric values are cast properly
    if ($attribute->type === 'number') {
        $filterValue = (float) $filterValue;
    }

    $query->whereHas('attributeValues', function ($q) use ($attribute, $operator, $filterValue) {
        $q->where('attribute_id', $attribute->id);

        if ($operator === 'like') {
            $q->where('value', 'LIKE', "%$filterValue%");
        } else {
            $q->whereRaw("CAST(value AS DECIMAL) $operator ?", [$filterValue]);
        }
    });
}



    /**
     * Apply filter for regular columns
     *
     * @param Builder $query
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function applyRegularFilter(Builder $query, string $key, $value): void
    {
        // Parse the operator from the value
        [$operator, $filterValue] = $this->parseOperator($value);

        // Apply the filter
        $query->where($key, $operator, $filterValue);
    }

    /**
     * Parse the operator from the given value
     *
     * @param mixed $value
     * @return array [$operator, $filterValue]
     */
    private function parseOperator($value): array
{
    $operator = '=';
    $filterValue = $value;

    if (is_string($value) && preg_match('/^([><]=?|like:|LIKE:|Like:)(.*)$/i', $value, $matches)) {
        $operator = strtolower($matches[1]); // Convert to lowercase
        $filterValue = trim($matches[2]); // Trim extra spaces

        if (in_array($operator, ['like:', 'LIKE:', 'Like:'], true)) {
            $operator = 'like'; // Normalize to 'like'
        }
    }

    return [$operator, $filterValue];
}

}
