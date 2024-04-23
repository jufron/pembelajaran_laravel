<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('token');
        if ($apiKey == 'token-valid') {
            return $next($request);
        } else {
            return response([
                'status'    => 401,
                'message'   => 'access dinaed'
            ], 401);
        }
    }
}
