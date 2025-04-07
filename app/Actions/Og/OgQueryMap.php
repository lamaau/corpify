<?php

namespace App\Actions\Og;

use Illuminate\Support\Collection;

class OgQueryMap
{
    public static function tree(Collection $positions)
    {
        return $positions->groupBy('category.position_category_name')
            ->flatMap(function ($group) {
                // Find the root (parent_id = null) for each category
                $root = $group->firstWhere('parent_id', null);

                // Ensure there is always one root per category
                if (!$root) {
                    $root = $group->first(); // Fallback: Select first entry if no null parent exists
                    $root->parent_id = null; // Force it to be root
                }

                // Convert the entire group to a flat array
                return $group->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'parentId' => $item->parent_id ? $item->parent_id : null, // Ensure null for top-level
                        'user' => $item->user?->profile,
                        'position' => [
                            'id' => $item->position?->id,
                            'name' => $item->position?->position_name,
                        ],
                        'category' => [
                            'id' => $item->category->id,
                            'name' => $item->category->position_category_name,
                            'summary' => $item->category->position_category_summary,
                        ],
                    ];
                });
            })->values();
    }
}
