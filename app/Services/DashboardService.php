<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class DashboardService
{
    /**
     * Get the status of all background jobs.
     *
     * @return array List of all jobs with their statuses.
     */
    public static function getAllJobStatuses()
    {        
        // Fetch all job status keys
        $jobKeys = Redis::keys(BackgroundJobRunner::JOB_STATUS_PREFIX . '*');
        $jobs = [];

        foreach ($jobKeys as $key) {
            // Remove the Redis prefix (if any) and the JOB_STATUS_PREFIX
            $rawKey = str_replace(config('database.redis.options.prefix'), '', $key);
            $jobId = str_replace(BackgroundJobRunner::JOB_STATUS_PREFIX, '', $rawKey);

            // Retrieve the job data and merge with the raw job ID due to laravel prefixing
            $jobData = Redis::hgetall($rawKey);
            $jobs[] = array_merge(['id' => $jobId], $jobData);
        }

        return $jobs;
    }
}
