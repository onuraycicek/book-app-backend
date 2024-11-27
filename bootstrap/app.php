<?php

use App\Http\Middleware\LocalizationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->append(StartSession::class)
            ->append(LocalizationMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ];

            $statusCode = $e->status ?? 400;
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
            }
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $statusCode = 401;
            }

            if ($e instanceof ValidationException) {
                $response['errors'] = $e->errors();
            }

            Log::channel('daily')->info(json_encode($response, JSON_PRETTY_PRINT));

            return response()->json($response, $statusCode);
        });
    })->create();
