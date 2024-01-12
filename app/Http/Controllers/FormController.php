<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class FormController extends Controller
{
    public function login (Request $request) : Response
    {
        try {
            $request->validate([
                'username'  => ['required', 'max:50'],
                'password'  => ['required', 'max:50']
            ]);
            return response('OK', Response::HTTP_OK);
        } catch (ValidationException $exceptin) {
            return response($exceptin->errors(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function form_login () : View
    {
        return view('validation');
    }

    public function login_post (LoginRequest $request) : Response
    {
        $request->validated();
        Log::info("hasil validation" . json_encode($request->all(), JSON_PRETTY_PRINT));
        return response('ok', Response::HTTP_OK);
    }
}
