<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Facades\DBLog;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Ensure 'dblog' service is available early in boot (fallback if provider hasn't bound yet)
        if (! app()->bound('dblog') && class_exists(\App\Services\DBLogService::class)) {
            // Bind a minimal instance; DBLogService has no required ctor dependencies in our implementation
            app()->singleton('dblog', function ($app) {
                return new \App\Services\DBLogService();
            });

            // Also bind the interface if used elsewhere
            if (interface_exists(\App\Contracts\DBLogInterface::class)) {
                app()->bind(\App\Contracts\DBLogInterface::class, function ($app) {
                    return $app->make('dblog');
                });
            }
        }

        // Capture all exceptions and send to DBLog (queued)
        $exceptions->report(function (\Throwable $e) {
            try {
                // Respect capture_env config if set
                $captureEnv = config('dblog.capture_env');
                if ($captureEnv && is_string($captureEnv)) {
                    $allowed = array_map('trim', explode(',', $captureEnv));
                    if (!in_array(app()->environment(), $allowed, true)) {
                        return;
                    }
                }

                if (app()->bound('dblog')) {
                    $dblog = app('dblog');
                    $dblog->withCustomLevel('exception')->error('Unhandled Exception', [
                        'exception' => [
                            'class' => get_class($e),
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                        ],
                        'description' => $e->getMessage(),
                    ]);
                } else {
                    \Log::error('Unhandled Exception (DBLog unavailable): '.$e->getMessage(), ['exception' => $e]);
                }
            } catch (\Throwable $inner) {
                \Log::error('DBLog failed during exception reporting: '.$inner->getMessage());
            }
        });

    })->create();
