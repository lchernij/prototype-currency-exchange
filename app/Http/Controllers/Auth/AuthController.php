<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }

    /**
     * Create a new user and return then
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->all());

        return response(AuthResource::make($user), 201);
    }

    /**
     * Return a user or throw unautorized exception
     */
    public function login(LoginRequest $request)
    {
        $user = $this->authService->tryAuth($request->all());

        if (empty($user)) {
            return response(json_encode(__('unauthorized')), 401);
        }

        return response(AuthResource::make($user), 200);
    }
}
