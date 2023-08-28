<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseController extends Controller
{
    public function response (): Response
    {
        return response('hello laravel', 201);
    }

    public function responseWithHeader ()
    {
        $body = [
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama'
        ];
        return response()
                    ->json($body)
                    ->header('Content-Type', 'application/json')
                    ->withHeaders([
                        'app'       => 'laravel',
                        'author'    => 'jufron'
                    ]);
    }

    public function responseView (): Response
    {
        $data = 'hello world';
        return response()->view('hello_world', compact('data'), 200);
    }

    public function responseFile (): BinaryFileResponse
    {
        return response()->file(Storage::path('public/document/coba.txt'));
    }

    public function responseDownload (): BinaryFileResponse
    {
        return response()->download(Storage::path('public/document/coba.txt'));
    }
}
