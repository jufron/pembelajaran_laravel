<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\Interface\UserService;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    protected UserService $userservice;

    public function __construct(UserService $userService)
    {
        $this->userservice = $userService;
    }

    public function login (): Response
    {
        return response()->view('auth.login', [
            'title' => 'login'
        ]);
    }

    public function actionLogin (Request $request): Response | RedirectResponse
    {
        $request->validate([
            'email'     => ['required'],
            'password'  => ['required']
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if ($this->userservice->login($email, $password)) {
            $request->session()->regenerate();
            $request->session() ->put('auth', $email);
            return redirect()->route('dashboard');
        }

        return redirect()->route('login')
                        ->withErrors('username atau password anda salah');
    }

    public function logout (Request $request): Response | RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('auth');
        return redirect()->route('home');
    }
}
