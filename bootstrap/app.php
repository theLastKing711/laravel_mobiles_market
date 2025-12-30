<?php

use App\Http\Middleware\Locale;
use App\Http\Middleware\OptionalAuthSanctum;
use App\Http\Middleware\ParseStringToBoolInQueryParameter;
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
    ->withMiddleware(function (Middleware $middleware) {
        // allows you to access routes without hitting /sanctum and sending request without csrf token
        // can be removed once we setup sanctum
        $middleware->validateCsrfTokens(except: ['*']);
        $middleware->statefulApi();
        $middleware->append(Locale::class);
        // 'true' or 'false' to true or false in query params for laravel-data and l5-swagger integration
        $middleware->append(ParseStringToBoolInQueryParameter::class);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'optional_auth' => OptionalAuthSanctum::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
