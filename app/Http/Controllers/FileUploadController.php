<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function upload (Request $request) : string
    {
        $picture = $request->file('picture');
        $picture->storePubliclyAs(
            'picture',
            $picture->getClientOriginalName(),
            'public'
        );

        return "ok " . $picture->getClientOriginalName();
    }

    public function uploadDoctPrivate (Request $request)
    {
        $fileDoct = $request->file('dokument')->storeAs('dokument', 'coba-file.pdf', 'private');
        $fileImage = $request->file('image')->storeAs('image', 'coba-file.jpg', 'private');

        return $fileDoct .' '. $fileImage;
    }

    public function uploadDoctPublic (Request $request)
    {
        $fileDoct = $request->file('dokument')->storeAs('dokument', 'coba-file.pdf', 'public');
        $fileImage = $request->file('image')->storeAs('image', 'coba-file.jpeg', 'public');
        $fileVideo = $request->file('video')->storeAs('video', 'coba-file.mp4', 'public');

        return $fileDoct .' '. $fileImage .' '. $fileVideo;
    }
}
