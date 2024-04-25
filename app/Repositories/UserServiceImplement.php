<?php


namespace App\Repositories;

use App\Contracts\UserService;

class UserServiceImplement implements UserService {

    private array $users = [
        'username'  => 'james',
        'password'  => 'rahasia'
    ];

    public function login(string $username, string $password): bool
    {
        if ($this->users['username'] === $username && $this->users['password'] === $password) {
            return true;
        }

        return false;
    }

    public function logout (): void
    {
        request()->session()->forget('user');
    }
}
