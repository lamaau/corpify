<?php

namespace App\Http\Controllers\Position;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\User\Position;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Position\StoreRequest;
use App\Http\Requests\Position\UpdateRequest;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Position::query()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Position::create($request->validated());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        return Response::success()->data($position)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Position $position)
    {
        return DB::transaction(function () use ($request, $position) {
            $position->update($request->validated());
            return Response::success()->data($position)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        return DB::transaction(function () use ($position) {
            $position->delete();
            return Response::success()->message('Succesfully');
        });
    }
}
