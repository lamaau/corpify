<?php

namespace App\Http\Controllers\Gallery;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Gallery\Gallery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\StoreRequest;
use App\Http\Requests\Gallery\UpdateRequest;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gallery::query()->with('media')->notFeatured()->orderBy('sort')
            ->applySearchWhen(value: $request->get('search'), columns: ['caption'])
            ->latest()->paginate($request->query('per_page', 8));

        return Response::success()->data($query)->appends([
            'meta' => [
                'featured' => Gallery::query()->with('media')->featured()->orderBy('sort')->take(8)->latest()->get(),
            ]
        ])->message('Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Gallery::create($request->getData());
            $query->addMedia($request->file('file'))->toMediaCollection('gallery');

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return Response::success()->data($gallery->loadMissing('media'))->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Gallery $gallery)
    {
        return DB::transaction(function () use ($gallery, $request) {
            $gallery->update($request->getData());

            if ($request->hasFile('file')) {
                $gallery->clearMediaCollection('gallery');
                $gallery->addMedia($request->file('file'))->toMediaCollection('gallery');
            }

            return Response::success()->data($gallery)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        return DB::transaction(function () use ($gallery) {
            $gallery->clearMediaCollection('gallery');
            $gallery->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
