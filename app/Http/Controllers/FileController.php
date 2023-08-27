<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload (Request $request) : string
    {
        $file = $request->file('avatar');
        $file->storePubliclyAs('avatar', $file->getClientOriginalName(), 'public');
        return "file berhasil diupload namanya : " . $file->getClientOriginalName();
    }
}
