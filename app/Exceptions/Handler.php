<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\View;
use Mockery\Exception\InvalidOrderException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'status_code' => 404,
                    'message' => 'Record not found.'
                ], 404);
            }
        });

        $this->renderable(function (InvalidOrderException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'status_code' => 500,
                    'message' => 'Internal server.'
                ], 500);
            }
        });
    }

    protected function registerErrorViewPaths()
    {
        parent::registerErrorViewPaths();

        if (request()->is('admin/*')) {
            View::prependNamespace(
                'errors',
                realpath(base_path('resources/views/errors/admin'))
            );
        }

        if (request()->is('trainer/*')) {
            View::prependNamespace(
                'errors',
                realpath(base_path('resources/views/errors/trainer'))
            );
        }

        if (request()->is('student/*')) {
            View::prependNamespace(
                'errors',
                realpath(base_path('resources/views/errors/student'))
            );
        }

        if (request()->is('agent/*')) {
            View::prependNamespace(
                'errors',
                realpath(base_path('resources/views/errors/agent'))
            );
        }

        if (request()->is('branch-user/*')) {
            View::prependNamespace(
                'errors',
                realpath(base_path('resources/views/errors/agentbranchuser'))
            );
        }
    }
}
