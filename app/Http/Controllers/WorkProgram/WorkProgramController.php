<?php

namespace App\Http\Controllers\WorkProgram;

use App\Actions\Response;
use App\Models\Program\WorkProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkProgram\StoreRequest;
use App\Http\Requests\WorkProgram\UpdateRequest;

class WorkProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WorkProgram::query()->with(['media'])
            ->applyFilterWhen(value: $request->get('status'), column: 'status')
            ->applySearchWhen(value: $request->get('search'), columns: ['title', 'body', 'summary', 'program.name'])
            ->latest()
            ->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = WorkProgram::create($request->getData());
            $query->addMedia($request->file('file'))->toMediaCollection('thumbnails');

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkProgram $workProgram)
    {
        return Response::success()->data($workProgram)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, WorkProgram $workProgram)
    {
        return DB::transaction(function () use ($request, $workProgram) {
            $workProgram->update($request->getData());

            if ($request->hasFile('file')) {
                $workProgram->clearMediaCollection('thumbnails');
                $workProgram->addMedia($request->file('file'))->toMediaCollection('thumbnails');
            }

            return Response::success()->data($workProgram)->message('Successfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkProgram $workProgram)
    {
        return DB::transaction(function () use ($workProgram) {
            $workProgram->clearMediaCollection('thumbnails');
            $workProgram->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
