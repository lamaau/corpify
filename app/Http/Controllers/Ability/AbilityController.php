<?php

namespace App\Http\Controllers\Ability;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Ability\Ability;
use App\Http\Controllers\Controller;

class AbilityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = Ability::query()->latest()->paginate($request->query('per_page', 100));

        return Response::success()->data($query)->message('Successfully');
    }
}
