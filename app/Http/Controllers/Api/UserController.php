<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUserRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index() : AnonymousResourceCollection
    {
        $user = auth()->user();

        $users = User::paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(StoreUserRequest $request) : UserResource
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateUserRequest
     * @param string $id
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->validated();

        $user = User::find($id);

        $user->update($data);

        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Illuminate\Http\Response
     */
    public function destroy(string $id) : Response
    {
        User::destroy($id);

        return $this->respondWithMessage('User successfully deleted.');
    }

    /**
     * Assign role to user
     *
     * @param \App\Http\Requests\AssignUserRoleRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function assignRole(AssignUserRoleRequest $request, string $id) : UserResource
    {
        $data = $request->validated();

        $user = User::find($id);

        $user->assignRole($data['role']);

        $user->refresh();

        return UserResource::make($user);
    }
}
