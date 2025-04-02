<?php

namespace App\Http\Controllers\Constant;

use App\Actions\Pagination;
use App\Actions\Response;
use App\Enums\Gallery\GalleryStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Response::success()
            ->data(
                Pagination::make(
                    GalleryStatus::labels($request->query('search')),
                    $request->query('per_page', 10)
                )
            )
            ->message('Succesfully');
    }
}
