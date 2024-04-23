<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SessionController extends Controller
{
    public function sessionCreate (): Response
    {
        session()->put('user_id', 'jufron');
        session()->put('is_admin', true);

        return response([
            'status'    => 200,
            'message'   => 'success'
        ]);
    }

    public function sessionGet (): Response
    {
        $user = null;
        $is_admin = null;

        if (session()->has('user')) {
            $user = session()->get('user');
        }

        if (session()->has('is_admin')) {
            $is_admin = session()->get('is_admin');
        }

        return response([
            'status'    => 200,
            'message'   => 'success',
            'user'      => $user,
            'is_admin'  => $is_admin
        ]);
    }
}
