<?php

namespace App\Http\Controllers\Regulation;

use App\Actions\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Regulation\Regulation;
use App\Http\Requests\Regulation\StoreRequest;
use App\Http\Requests\Regulation\UpdateRequest;

class RegulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Regulation::query()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Regulation::create($request->getData());
            $query->addMedia($request->file('file'))->toMediaCollection('regulations');

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Regulation $regulation)
    {
        return Response::success()->data($regulation)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Regulation $regulation)
    {
        return DB::transaction(function () use ($regulation, $request) {
            $regulation->update($request->getData());

            if ($request->hasFile('file')) {
                $regulation->clearMediaCollection('regulations');
                $regulation->addMedia($request->file('file'))->toMediaCollection('regulations');
            }

            return Response::success()->data($regulation)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regulation $regulation)
    {
        return DB::transaction(function () use ($regulation) {
            $regulation->clearMediaCollection('regulations');
            $regulation->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
