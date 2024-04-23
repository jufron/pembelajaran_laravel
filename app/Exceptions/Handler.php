<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport  = [
        ValidationException::class,
    ];

    protected $withoutDuplicates = true;

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {

        });

        $this->reportable(function (ContohException $e) {
            Log::error('message error : ' . json_encode([
                'message'       => $e->getMessage(),
                'file'          => $e->getFile(),
                'code'          => $e->getCode(),
                'line'          => $e->getLine(),
                'trace'         => $e->getTrace()
            ]), JSON_PRETTY_PRINT);
        })->stop();

        $this->renderable(function (ContohException $e) {
            response()->json([
                'error'     => 'something when wrong',
                'message'   => [
                    'message_error' => $e->getMessage(),
                    'file'          => $e->getFile(),
                    'code'          => $e->getCode(),
                    'line'          => $e->getLine(),
                    'trace'         => $e->getTrace()
                ]
            ]);
            return false;
        });
    }
}
