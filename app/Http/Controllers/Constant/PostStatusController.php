<?php

namespace App\Http\Controllers\Constant;

use App\Actions\Pagination;
use App\Actions\Response;
use Illuminate\Http\Request;
use App\Enums\Post\PostStatus;
use App\Http\Controllers\Controller;

class PostStatusController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Response::success()
            ->data(
                Pagination::make(
                    PostStatus::labels($request->query('search')),
                    $request->query('per_page', 10)
                )
            )
            ->message('Succesfully');
    }
}
