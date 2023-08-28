<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CookiesController extends Controller
{
    public function cookiesSet (): JsonResponse
    {
        // set cokokies
        $data = ['app_name' => 'laravel 10'];
        return response()->json($data)
                         ->cookie('login-with', 'google', 6000)
                         ->cookie('author', 'jufron');
    }

    public function getCookies (Request $request): JsonResponse
    {
        $data = [
            'login-with'    => $request->cookie('login-with'),
            'author'        => $request->cookie('author')
        ];
        return response()->json($data);
    }

    public function cookiesExpire ()
    {
        return response('remove cookies')
                ->withoutCookie('author')
                ->withoutCookie('login-with');
    }
}
