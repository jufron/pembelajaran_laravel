<?php


namespace App\Contracts;


interface UserService {

    public function login(string $username, string $password): bool;

    public function logout(): void;
}
