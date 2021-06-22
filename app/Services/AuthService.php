<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Creatre a new User and return
     */
    public function tryAuth(array $fields): ?User
    {
        if (Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']])) {
            return Auth::user();
        }
        
        return null;
    }
}
