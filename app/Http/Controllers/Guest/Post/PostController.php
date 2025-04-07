<?php

namespace App\Http\Controllers\Guest\Post;

use App\Actions\Response;
use App\Models\Post\Post;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Post $post)
    {
        return Response::success()->data($post)->message('Successfully');
    }
}
