<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    }

    public function renderx($request, Throwable $exception)
    {
        // dd($exception);

        // Handle ModelNotFoundException and NotFoundHttpException (404 Not Found errors)
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            // dd("Inside exception");
            // Session::flash('error', 'Failed to handle an invalid request!'); // Set the error message in session flash data
            return redirect()->route('dashboard')->with('error', 'Failed to handle an invalid request!');; // Change '/dashboard' to the URL you want to redirect to
        }

        return parent::render($request, $exception);
    }
}