<?php

namespace App\Http\Controllers\User;

use App\Actions\Response;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $relations = $request->getRelationsRequest();

        $searchFields = $request->mapSearchFieldsWithRelations(
            columns: ['email', 'phone_number'],
            relationColumns: ['profile' => ['profile.name', 'profile.nip']],
        );

        $query = User::query()->whereHas('profile')
            ->when($relations, fn(Builder $query) => $query->with($relations))
            ->applySearchWhen(value: $request->get('search'), columns: $searchFields)
            ->latest()->paginate($request->query('per_page', 10));

        return Response::success()->data($query)->message('Succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
