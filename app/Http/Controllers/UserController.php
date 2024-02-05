<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register (UserRegisterRequest $request) : JsonResponse | UserResource
    {
        $user_validated = $request->validated();

        $dataIsExcist = User::where('username', $user_validated['username'])->count() == 1;

        if ($dataIsExcist) {
            throw new HttpResponseException(response([
                'errors' => [
                    'username' => [
                        'The username has already been taken.'
                    ]
                ]
            ], 422));
        }

        $newUser = User::create([
            'username'  => $user_validated['username'],
            'password'  => Hash::make($user_validated['password']),
            'name'      => $user_validated['name']
        ]);

        return (new UserResource($newUser))->response()->setStatusCode(201);
    }

    public function login (UserLoginRequest $request) : JsonResponse | UserResource
    {
        $user_validated = $request->validated();

        $user = User::where('username', $user_validated['username'])->get()->first();

        // check compare username & password match
        if (!$user || !Hash::check($user_validated['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'The username or password wrong.'
                    ]
                ]
            ], 401));
        }

        $user->update([
            'token' => Str::uuid()
        ]);

        return new UserResource($user);
    }

    public function get () : UserResource
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function update (UserUpdateRequest $request)
    {
        $userValidated = $request->validated();

        $userCurrent = Auth::user();

        if (!empty($userValidated['name'])) {
            $userCurrent->update([
                'name'      => $userValidated['name']
            ]);
        }

        if (!empty($userValidated['password'])) {
            $userCurrent->update([
                'password'  => Hash::make($userValidated['password'])
            ]);
        }

        return new UserResource($userCurrent);
    }

    public function logout () : JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'token' => null
        ]);

        auth()->logout();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
