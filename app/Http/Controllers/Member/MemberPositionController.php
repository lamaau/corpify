<?php

namespace App\Http\Controllers\Member;

use App\Actions\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Member\MemberPosition;
use App\Http\Requests\Member\MemberPositionFormRequest;

class MemberPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MemberPosition::query()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberPositionFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $query = MemberPosition::create($request->validated());

            return Response::success()->data($query)->message('Succesfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberPosition $memberPosition)
    {
        return Response::success()->data($memberPosition)->message('Succesfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberPositionFormRequest $request, MemberPosition $memberPosition)
    {
        return DB::transaction(function () use ($memberPosition, $request) {
            $memberPosition->update($request->validated());

            return Response::success()->data($memberPosition)->message('Succesfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberPosition $memberPosition)
    {
        return DB::transaction(function () use ($memberPosition) {
            $memberPosition->delete();

            return Response::success()->message('Succesfully');
        });
    }
}
