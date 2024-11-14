<?php

namespace App\Services;

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
            $jobData = Redis::hgetall($key);
            $jobs[] = array_merge(['id' => str_replace(BackgroundJobRunner::JOB_STATUS_PREFIX, '', $key)], $jobData);
        }

        return $jobs;
    }
}
