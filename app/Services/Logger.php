<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Logger 
{
    public static function info(mixed $message, array $context = []): void
    {
        self::logJob($context['job_id'], $message);
        Log::channel('background_jobs_info')->info($message, $context);
    }

    public static function error(mixed $message, array $context = []): void
    {
        self::logJob($context['job_id'], $message . '. | Error: ' . $context['error']);
        Log::channel('background_jobs_error')->error($message, $context);
    }

    /**
     * Log information about a job in Redis.
     */
    private static function logJob($jobId, $message)
    {
        $timestamp = now()->toDateTimeString();
        Redis::rpush(BackgroundJobRunner::JOB_LOGS_PREFIX . $jobId, "[$timestamp] $message");
    }
}
