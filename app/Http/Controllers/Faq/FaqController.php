<?php

namespace App\Http\Controllers\Faq;

use App\Actions\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\FaqRequest;
use App\Models\Faq\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Faq::query()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = Faq::create($request->getData());
            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        return Response::success()->data($faq)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        return DB::transaction(function () use ($faq, $request) {
            $faq->update($request->getData());
            return Response::success()->data($faq)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        return DB::transaction(function () use ($faq) {
            $faq->delete();
            return Response::success()->message('Succesfully');
        });
    }
}
