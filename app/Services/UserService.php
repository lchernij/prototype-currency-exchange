<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Creatre a new User and return
     */
    public function create(array $fields): User
    {
        $fields['password'] = bcrypt($fields['password']);
        
        $user = User::create($fields);

        return $user;
    }
}