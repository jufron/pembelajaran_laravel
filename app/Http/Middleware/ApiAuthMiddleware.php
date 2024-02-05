<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('authorization');
        $authenticate = true;

        // check header auth excsist
        if (!$token) {
            $authenticate = false;
        }

        $user = User::where('token', $token)->get()->first();

        // check header auth excsist and valid database
        if (!$user) {
            $authenticate = false;
        } else {
            Auth::login($user);
        }

        if ($authenticate) {
            return $next($request);
        } else {
            // return response()->json([
            //     'errors' => [
            //         'message' => [
            //             'unauthorize'
            //         ]
            //     ]
            // ])->setStatusCode(401);

            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'unauthorize'
                    ]
                ]
            ], 401));
        }

    }
}
