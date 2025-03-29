<?php

namespace App\Http\Controllers\Gallery;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Models\Gallery\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\StoreRequest;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Gallery::query()->with('media')->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully get galleries');
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
