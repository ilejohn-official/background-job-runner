<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Logger 
{
    public static function info(mixed $message, array $context = []): void
    {
        Log::channel('background_jobs_info')->info($message, $context);
    }

    public static function error(mixed $message, array $context = []): void
    {
        Log::channel('background_jobs_error')->error($message, $context);
    }
}
