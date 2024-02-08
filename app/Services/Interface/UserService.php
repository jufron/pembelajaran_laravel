<?php

namespace App\Services\Interface;

interface UserService {

    public function login (string $email, string $password): bool;
}
