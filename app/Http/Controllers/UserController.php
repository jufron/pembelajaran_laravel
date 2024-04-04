<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login (Request $request)
    {
        $response = Auth::attempt([
            'email'     => $request->input('email', 'wrong'),
            'password'  => $request->input('password', 'wrong')
        ]);

        if ($response) {
            Session::regenerate();
            return redirect()->route('dashboard');
        }

        return 'wrong credencial';
    }

    public function currentUser ()
    {
        $user = Auth::user();
        // return $user ? 'hello : ' . $user->email : 'hello guest';

        if ($user) {
            if (isset($user->email) && $user->email !== null) {
                return "hello $user->email";
            }
            return "hello $user->name";
        }
        return "hello guest";
    }
}
