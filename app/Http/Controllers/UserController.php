<?php

namespace App\Http\Controllers;

use App\Contracts\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login ()
    {
        return response()->view('auth.login', [
            'title' => 'Login'
        ]);
    }

    public function todoLogin (Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (empty($username) || !isset($username) || $username === '' || empty($password) || !isset($password) || $password === '') {
            return response()->view('auth.login', [
                'title' => 'Login',
                'error' => 'user or password is required'
            ], 302);
        }

        if (!$this->userService->login($username, $password)) {
            return response()->view('auth.login', [
                'title' => 'Login',
                'error' => 'user or password is wrong'
            ], 302);
        }

        $request->session()->put('user');
        return redirect()->route('home');
    }

    public function logout () : RedirectResponse
    {
        $this->userService->logout();
        return redirect()->route('todolist.login');
    }
}
