<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RegisterResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->userService = new UserService();    
    }

    /**
     * Create a new user and return then
     */
    public function Register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->all());
        return response(RegisterResource::make($user), 201);
    }
}
