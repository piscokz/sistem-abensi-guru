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
    ->withMiddleware(function (Middleware $middleware): void {
        // Pendaftaran Middleware Global
        // Middleware yang akan berjalan di setiap request (Web & API).
        // Anda bisa tambahkan middleware lain yang selalu dibutuhkan di sini.


        // Pendaftaran Middleware Grup (Middleware aliases)
        // Ini adalah cara untuk memberikan nama singkat ke sekelompok middleware.
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Pendaftaran Middleware Alias
        // Ini adalah cara untuk memberikan nama singkat ke satu middleware.
        $middleware->alias([
            'role' => \App\Http\Middleware\CekRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();