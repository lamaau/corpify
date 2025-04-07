<?php

namespace App\Http\Controllers\Guest\Post;

use App\Actions\Response;
use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $query = Post::query()->published()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }
}
