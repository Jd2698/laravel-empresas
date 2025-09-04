<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    }

    /**
     * Renderiza la excepción para una respuesta HTTP.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        // Manejo de ModelNotFoundException (cuando un modelo no es encontrado)
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'The requested resource was not found.',
            ], 404);
        }

        // Manejo de excepciones de validación
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => $exception->errors(),
            ], 422);
        }

        // Manejo de excepciones HTTP, como 401, 403, etc.
        if ($exception instanceof HttpException) {
            return response()->json([
                'error' => 'HTTP Exception',
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        // Manejo de excepciones generales
        return parent::render($request, $exception);
    }
}
