<?php

namespace App\Http\Controllers\PositionCategory;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\PositionCategory;
use App\Http\Requests\PositionCategory\StoreRequest;
use App\Http\Requests\PositionCategory\UpdateRequest;
use Illuminate\Support\Facades\DB;

class PositionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PositionCategory::query()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = PositionCategory::create($request->validated());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(PositionCategory $positionCategory)
    {
        return Response::success()->data($positionCategory)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PositionCategory $positionCategory)
    {
        return DB::transaction(function () use ($request, $positionCategory) {
            $positionCategory->update($request->validated());

            return Response::success()->data($positionCategory)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionCategory $positionCategory)
    {
        return DB::transaction(function () use ($positionCategory) {
            $positionCategory->delete();
            return Response::success()->message('Succesfully');
        });
    }
}
