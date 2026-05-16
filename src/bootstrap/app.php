<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\QueryException $e) {
            // Check if the error is a connection failure (e.g., offline)
            if (str_contains($e->getMessage(), 'could not translate host name') || 
                str_contains($e->getMessage(), 'Connection refused') ||
                str_contains($e->getMessage(), 'SQLSTATE[08006]')) {
                return response()->view('errors.offline', [], 503);
            }
        });
    })->create();
