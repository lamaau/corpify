<?php

namespace App\Http\Controllers\Gallery;

use App\Actions\Response;
use App\Models\Gallery\Gallery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\SortRequest;

class GallerySortController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SortRequest $request)
    {
        return DB::transaction(function () use ($request) {
            foreach ($request->featured as $item) {
                Gallery::where('id', $item['id'])->update(['sort' => $item['sort'], 'featured' => true]);
            }

            foreach ($request->gallery as $item) {
                Gallery::where('id', $item['id'])->update(['sort' => $item['sort'], 'featured' => false]);
            }

            return Response::success()->message('Successfully');
        });
    }
}
