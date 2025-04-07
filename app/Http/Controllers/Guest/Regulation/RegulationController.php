<?php

namespace App\Http\Controllers\Guest\Regulation;

use App\Actions\Response;
use App\Http\Controllers\Controller;
use App\Models\Regulation\Regulation;

class RegulationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Regulation $regulation)
    {
        return Response::success()->data($regulation)->message('Succesfully');
    }
}
