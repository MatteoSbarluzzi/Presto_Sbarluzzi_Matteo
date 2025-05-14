<?php

use App\Http\Middleware\IsRevisor; // 
use Illuminate\Foundation\Application;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    //configurazione dei middleware per questo gruppo di rotte
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [SetLocaleMiddleware::class]);//append ci consente di aggiungere il middleware al middleware esistente per le rotte web. Gestisce il cambio lingua in modo automatico (es: locale da URL o sessione)
        $middleware->alias([
            'isRevisor' => IsRevisor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
