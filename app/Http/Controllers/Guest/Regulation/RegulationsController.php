<?php

namespace App\Http\Controllers\Guest\Regulation;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Regulation\Regulation;

class RegulationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = Regulation::query()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }
}
