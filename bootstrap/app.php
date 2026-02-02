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
        
        // 1. Middleware Globales o Web (Aquí va la Zona Horaria)
        $middleware->web(append: [
            \App\Http\Middleware\SetTimezone::class,
        ]);

        // 2. Alias para usar en las rutas (SOLUCIÓN AL ERROR)
        // Esto le dice a Laravel: "Cuando veas 'role', usa este archivo CheckRole"
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();