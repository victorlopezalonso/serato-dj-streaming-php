<?php

namespace App\Exceptions;

use http\Client\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Exception $e) {
            return response()->json([
                'file' => $e->getFile() . ':' . $e->getLine(),
                'message' => $e->getMessage(),
            ], 500, [], JSON_PRETTY_PRINT);
        });
    }
}
