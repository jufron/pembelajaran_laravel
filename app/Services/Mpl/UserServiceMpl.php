<?php

namespace App\Services\Mpl;

use App\Services\Interface\UserService;

class UserServiceMpl implements UserService
{
    private array $user = [
        'username'  => 'james',
        'password'  => '12345678'
    ];

    public function login(string $user, string $password): bool
    {
        if ($this->user['username'] === $user && $this->user['password'] === $password) {
            return true;
        } else {
            return false;
        }
    }
}
