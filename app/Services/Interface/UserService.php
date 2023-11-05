<?php

namespace App\Services\Interface;

interface UserService {

    public function login (string $user, string $password): bool;
}
