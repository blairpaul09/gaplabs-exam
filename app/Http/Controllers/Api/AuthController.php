<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidCredentialExeception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * Attempt to authenticate user
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \App\Http\Resources\LoginResource
     */
    public function login(LoginRequest $request) : LoginResource
    {
        $credentials = $request->validated();

        $token = auth()->attempt($credentials);

        throw_if(!$token, new InvalidCredentialExeception());

        $data = [
            'access_token' => $token
        ];

        return LoginResource::make($data);
    }

    /**
     * Get the authenticated user
     *
     * @return \App\Http\Resources\UserResource
     */
    public function me() : UserResource
    {
        $user = auth()->user();

        return UserResource::make($user);
    }

    /**
     * Logout the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() : Response
    {
        auth()->logout();

        return $this->respondWithMessage('User successfully logged out.');
    }
}
