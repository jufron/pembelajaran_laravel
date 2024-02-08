<?php

namespace App\Services\Mpl;

use App\Services\Interface\UserService;
use Illuminate\Support\Facades\Auth;

class UserServiceMpl implements UserService
{
    public function login(string $email, string $password): bool
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }
}
