<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function createCookie () : JsonResponse
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'success login'
        ])
        ->cookie('login_with', 'google')
        ->cookie('rolle_user', 'admin');
    }

    public function getCookie () : JsonResponse
    {
        if (request()->hasCookie('login_with') == 'google') {
            return response()->json([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => request()->cookie('login_with')
            ]);
        } else if (request()->hasCookie('login_with') == 'microsoft') {
            return response()->json([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => request()->cookie('login_with')
            ]);
        } else if (request()->hasCookie('login_with') == 'facebook') {
            return response()->json([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => request()->cookie('login_with')
            ]);
        }
        return response()->json([
            'page'      => 'dashboard',
            'status'    => 200,
            'message'   => 'success'
        ]);
    }
}
