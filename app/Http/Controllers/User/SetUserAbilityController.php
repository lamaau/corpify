<?php

namespace App\Http\Controllers\User;

use App\Actions\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AssignAbilityRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class SetUserAbilityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AssignAbilityRequest $request, User $user)
    {
        return DB::transaction(function () use ($request, $user) {
            $user->syncRole($request->role_id);

            return Response::success()->message('Successfully');
        });
    }
}
