<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputController extends Controller
{
    public function hello (Request $request): string
    {
        $nama = $request->input('nama');
        return "hallo $nama";
    }

    public function helloNested (Request $request): string
    {
        $firstName = $request->input('nama.first');
        $lastName = $request->input('nama.last');
        return "hallo $firstName $lastName";
    }
}
