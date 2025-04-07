<?php

namespace App\Http\Controllers\Guest\Gallery;

use App\Actions\Response;
use App\Http\Controllers\Controller;
use App\Models\Gallery\Gallery;
use Illuminate\Http\Request;

class GalleryFeaturedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Response::success()->data(Gallery::query()->featured()->take(8)->get())->message('Successfully');
    }
}
