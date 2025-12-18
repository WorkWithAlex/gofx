<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void log(string $level, string $message, array $context = [])
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static \App\Contracts\DBLogInterface withLoggedBy(string $name)
 * @method static \App\Contracts\DBLogInterface withCustomLevel(string $level)
 */
class DBLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dblog';
    }
}
