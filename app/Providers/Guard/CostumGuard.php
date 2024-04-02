<?php


namespace App\Providers\Guard;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;

class CostumGuard implements Guard {

    use GuardHelpers;

    protected $user;

    public function user()
    {
        return $this->user;
    }

    public function validate(array $credentials = []) : bool
    {
        $user = User::query()->where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $this->user();
            return true;
        }

        return false;
    }
}
