<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function sessionSet (Request $request): string
    {
        $request->session()->put('user', 'james');
        $request->session()->put('login_with', 'google');
        return 'session di set';
    }

    public function sessionGet (Request $request): string
    {
        $user = $request->session()->get('user');
        $login_with = $request->session()->get('login_with');
        return 'data session user : '. $user . ' login with : '. $login_with;
    }
}
