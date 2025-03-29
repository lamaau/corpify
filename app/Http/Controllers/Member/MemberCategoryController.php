<?php

namespace App\Http\Controllers\Member;

use App\Actions\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\MemberCategoryFormRequest;
use App\Models\Member\MemberCategory;

class MemberCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MemberCategory::query()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberCategoryFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = MemberCategory::create($request->validated());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberCategory $memberCategory)
    {
        return Response::success()->data($memberCategory)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberCategoryFormRequest $request, MemberCategory $memberCategory)
    {
        return DB::transaction(function () use ($memberCategory, $request) {
            $memberCategory->update($request->validated());

            return Response::success()->data($memberCategory)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberCategory $memberCategory)
    {
        return DB::transaction(function () use ($memberCategory) {
            $memberCategory->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
