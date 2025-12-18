<?php

namespace App\Providers;

use App\Contracts\DBLogInterface;
use App\Services\DBLogService;
use Illuminate\Support\ServiceProvider;

class DBLogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dblog', function ($app) {
            return new DBLogService();
        });

        $this->app->bind(DBLogInterface::class, function ($app) {
            return $app->make('dblog');
        });
    }

    public function boot()
    {
        // publish config if needed (optional)
        $this->publishes([
            __DIR__ . '/../../config/dblog.php' => config_path('dblog.php'),
        ], 'config');
    }
}
