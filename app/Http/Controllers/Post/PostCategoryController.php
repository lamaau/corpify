<?php

namespace App\Http\Controllers\Post;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Post\PostCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategory\StoreRequest;
use App\Http\Requests\PostCategory\UpdateRequest;

class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PostCategory::query()
            ->applySearchWhen(value: $request->get('search'), columns: [
                'category_name',
                'category_summary'
            ])
            ->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PostCategory $postCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PostCategory $postCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCategory $postCategory)
    {
        //
    }
}
