<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseController extends Controller
{
    public function responseHeader(Request $request): Response
    {
        $body = [
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com'
        ];

        return response(
                content: $body,
                status: 200
            )
            ->header('Content-Type', 'application/json')
            ->withHeaders([
                'author'    => 'james',
                'app'       => 'belajar laravel'
            ]);
    }

    public function responseHeader2 (Request $request): JsonResponse
    {
        return response()->json([
            'firstName'     => $request->input('firstName'),
            'lastName'      => $request->input('lastName'),
            'email'         => $request->input('email')
        ])
        ->header('Content-Type', 'application/json');
    }

    public function responseView (Request $request): Response
    {
        return response()->view('siswa', [
            'nama'  => 'james'
        ]);
    }

    public function responseFile (Request $request): BinaryFileResponse
    {
        $header = ['Content-Type' => 'image/jpeg'];

        return response()->file(
            storage_path('app/public/image/designer.jpeg')
        );
    }

    public function responseDownload (Request $request): BinaryFileResponse
    {
        return response()
            ->download(storage_path('app/public/image/designer.jpeg'));
    }
}
