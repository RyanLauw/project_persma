<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan alias middleware di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/register/step-1',
            '/register/step-2',
            '/register/step-3',
            '/register/step-4',
            '/register/submit'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();