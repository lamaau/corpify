<?php

namespace App\Http\Controllers\News;

use App\Actions\Response;
use App\Models\News\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\StoreRequest;
use App\Http\Requests\News\UpdateRequest;
use Illuminate\Database\Eloquent\Builder;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $query = News::query()->with('media')
            ->when($keyword, function (Builder $query) use ($keyword) {
                $query->whereLike('title', "%{$keyword}%")
                    ->orWhereHas('content', function (Builder $subQuery) use ($keyword) {
                        $subQuery->whereLike('content', "%{$keyword}%");
                    });
            })
            ->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = News::create($request->getNewsData());
            $query->addMedia($request->file('file'))->toMediaCollection('thumbnail');
            $query->contentRelation()->create($request->getNewsContent());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        return Response::success()->data($news)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, News $news)
    {
        return DB::transaction(function () use ($request, $news) {
            $news->update($request->getNewsData());

            if ($request->hasFile('file')) {
                $news->clearMediaCollection('thumbnail');
                $news->addMedia($request->file('file'))->toMediaCollection('thumbnail');
            }

            $news->contentRelation()->update($request->getNewsContent());

            return Response::success()->data($news)->message('Successfully');
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        return DB::transaction(function () use ($news) {
            $news->clearMediaCollection('thumbnail');
            $news->contentRelation()->delete();
            $news->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
