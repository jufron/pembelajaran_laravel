<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContohException extends Exception
{
    public function report () : void
    {

    }

    public function render (Request $request)
    {

    }

    // public function report ()
    // {
    //     Log::error('message error : ' . json_encode([
    //         'message'       => $this->getMessage(),
    //         'file'          => $this->getFile(),
    //         'code'          => $this->getCode(),
    //         'line'          => $this->getLine(),
    //         'trace'         => $this->getTrace()
    //     ]), JSON_PRETTY_PRINT);
    // }

    // public function render ()
    // {
    //     return response()->json([
    //         'error'     => 'something when wrong',
    //         'message'   => [
    //             'message_error' => $this->getMessage(),
    //             'file'          => $this->getFile(),
    //             'code'          => $this->getCode(),
    //             'line'          => $this->getLine(),
    //             'trace'         => $this->getTrace()
    //         ]
    //     ]);
    // }

    public function context (): array
    {
        return ['order_id' => $this->orderId];
    }
}
