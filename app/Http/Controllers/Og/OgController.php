<?php

namespace App\Http\Controllers\Og;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Og\StoreRequest;
use App\Http\Requests\Og\UpdateRequest;
use App\Models\User\PositionAssignment;
use Illuminate\Support\Facades\DB;

class OgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PositionAssignment::with(['user.profile', 'position', 'category'])->get()
            ->groupBy('category.position_category_name')
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

        $grouped = $query->groupBy('category.name');

        return Response::success()->data($grouped)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = PositionAssignment::create($request->validated());

            return Response::success()->data($query->loadMissing([
                'user.profile',
                'position',
                'category',
                'parent'
            ]))->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionAssignment $og)
    {
        return Response::success()->data($og->loadMissing([
            'user.profile',
            'position',
            'category',
            'parent',
            'children',
        ]))->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PositionAssignment $og)
    {
        return DB::transaction(function () use ($og, $request) {
            if (!$og->parent()->exists()) {
                // update category details here
            } else {
                $og->update($request->validated());
            }

            return Response::success()->data($og)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionAssignment $og)
    {
        return DB::transaction(function () use ($og) {
            // 😎 also delete position category if root of tree deleted
            if (!$og->parent()->exists()) {
                $og->category->delete();
            }

            $og->children()->delete();
            $og->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
