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
        // 🔴 1. Daftarkan alias middleware role bawaan lu
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 🔴 2. Kunci Pengaman Ngrok: Biar gak Error 419 / Bad Request saat login
        $middleware->trustProxies(at: '*');

        // 🔴 3. Enforcer HTTPS: Biar link download rekap di HP via Ngrok gak di-blokir browser
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \URL::forceScheme('https');
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();