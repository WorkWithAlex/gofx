<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule pruning of system logs every day at 02:00
Schedule::command('dblog:prune')->dailyAt('02:00');