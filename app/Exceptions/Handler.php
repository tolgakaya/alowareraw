<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->renderable(function(\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'result' => 1,
                'errors' => $e->errors()
            ], 422);
        });
        $this->renderable(function(\App\Exceptions\MaximumDepthException $e, $request) {
            return response()->json("Already reached the maximum comment depth!!!", 422);
        });
    }
}
