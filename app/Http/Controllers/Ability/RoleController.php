<?php

namespace App\Http\Controllers\Ability;

use App\Actions\Response;
use App\Models\Ability\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ability\StoreRequest;
use App\Http\Requests\Ability\UpdateRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query()->with(['abilities'])->public()->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $role = Role::create($request->getRoleData());
            $role->abilities()->sync($request->getAbilityData());

            return Response::success()->data($role->loadMissing('abilities'))->message('Successfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return Response::success()->data($role->loadMissing(['abilities']))->message('Successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Role $role)
    {
        return DB::transaction(function () use ($request, $role) {
            $role->update($request->getRoleData());
            $role->abilities()->sync($request->getAbilityData());

            return Response::success()->data($role->loadMissing(['abilities']))->message('Successfully');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        return DB::transaction(function () use ($role) {
            $role->delete();

            return Response::success()->message('Successfully');
        });
    }
}
