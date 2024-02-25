<?php

namespace App\Exceptions;

use Core\Http\Resources\ErrorResource;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

        $this->renderable(function (Throwable $e, Request $request) {
            if (config('app.debug')) return;

            if ($e instanceof ValidationException) return;

            return $this->getErrorResponse($e);
        });
    }

    /**
     * Get response for the given exception.
     *
     * @param Throwable $e
     * @return JsonResponse
     */
    protected function getErrorResponse(Throwable $e): JsonResponse
    {
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();
        } else {
            $statusCode = 500;
            $message = trans('response.error');
        }

        return (new ErrorResource($e))
            ->additional([
                'code' => $statusCode,
                'message' => $message,
            ])
            ->response()
            ->setStatusCode($statusCode);
    }
}
