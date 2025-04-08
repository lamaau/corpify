<?php

namespace App\Http\Controllers\Program;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Program\Program;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest\StoreRequest;
use App\Http\Requests\ProgramRequest\UpdateRequest;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $relations = $request->getRelationsRequest();

        $query = Program::query()->with($relations)
            ->applySearchWhen(value: $request->get('search'), columns: $request->mapSearchFieldsWithRelations(
                columns: ['name'],
                relationColumns: [
                    'features' => 'features.feature_name',
                    'works' => ['works.title', 'works.summary'],
                ],
            ))
            ->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Program::create($request->getProgramData());
            $query->features()->createMany($request->getProgramFeaturesData());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        return Response::success()
            ->message('Succesfully')
            ->data($program->loadMissing('features'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Program $program)
    {
        return DB::transaction(function () use ($request, $program) {
            $program->update($request->getProgramData());

            $program->features()->delete();
            $program->features()->createMany($request->getProgramFeaturesData());

            return Response::success()
                ->message('Succesfully update program')
                ->data($program->loadMissing('features'));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        return DB::transaction(function () use ($program) {
            $program->features()->delete();
            $program->delete();

            return Response::success()->message('Succesfully delete program');
        });
    }
}
