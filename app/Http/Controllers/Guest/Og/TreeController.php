<?php

namespace App\Http\Controllers\Guest\Og;

use App\Actions\Response;
use App\Actions\Og\OgQueryMap;
use App\Http\Controllers\Controller;
use App\Models\User\PositionAssignment;

class TreeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $query = OgQueryMap::tree(PositionAssignment::with(['user.profile', 'position', 'category'])->get())
            ->groupBy('category.name');

        return Response::success()->data($query)->message('Succesfully');
    }
}
