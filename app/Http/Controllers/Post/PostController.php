<?php

namespace App\Http\Controllers\Post;

use App\Actions\Response;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query()->with('media')
            ->applySearchWhen(value: $request->get('search'), columns: ['title', 'body'])
            ->applyFilterWhen(value: $request->get('status'), column: 'status')
            ->latest()
            ->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Post::create($request->getData());
            $query->addMedia($request->file('file'))->toMediaCollection('thumbnails');

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Response::success()->data($post)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        return DB::transaction(function () use ($request, $post) {
            $post->update($request->getData());

            if ($request->hasFile('file')) {
                $post->clearMediaCollection('thumbnails');
                $post->addMedia($request->file('file'))->toMediaCollection('thumbnails');
            }

            return Response::success()->data($post)->message('Successfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        return DB::transaction(function () use ($post) {
            $post->clearMediaCollection('thumbnails');
            $post->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
