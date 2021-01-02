<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // detect instance
        if ($exception instanceof UnauthorizedHttpException) {
            // detect previous instance
            if ($exception instanceof TokenExpiredException) {
                return response()->json(['message' => 'Expired Token'], $exception->getStatusCode());
            } else if ($exception instanceof TokenInvalidException) {
                return response()->json(['message' => 'Invalid Token'], $exception->getStatusCode());
            } else if ($exception instanceof TokenBlacklistedException) {
                return response()->json(['message' =>'Blacklisted Token'], $exception->getStatusCode());
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        return parent::render($request, $exception);
    }
}
