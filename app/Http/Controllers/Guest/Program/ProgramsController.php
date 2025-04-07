<?php

namespace App\Http\Controllers\Guest\Program;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Program\Program;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class ProgramsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $relations = $request->getRelationsRequest();

        $query = Program::query()
            ->when($relations, fn(Builder $query, $relations) => $query->with($relations))
            ->applySearchWhen(value: $request->get('search'), columns: Program::mapSearchColumns($relations))
            ->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }
}
