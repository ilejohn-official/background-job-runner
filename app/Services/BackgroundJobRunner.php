<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\BackgroundJob;
use Illuminate\Support\Str;

class BackgroundJobRunner
{
    const JOB_QUEUE = 'background_jobs:queue';
    const JOB_STATUS_PREFIX = 'background_jobs:status:';

    /**
     * Queue a job with priority, status, and metadata tracking.
     */
    public static function queueJob($class, $method, $parameters = [], $priority = 0, $delay = 0)
    {
        $jobId = Str::uuid()->toString();
        $runAt = now()->addSeconds($delay)->timestamp;

        $job = new BackgroundJob($jobId, $class, $method, $parameters, $priority, 0, $runAt);

        // Serialize and push to Redis sorted set based on priority (lower score = higher priority)
        $priorityScore = $priority > 0 ? 0 : 1;
        Redis::zadd(self::JOB_QUEUE, $priorityScore, json_encode($job->toArray()));

        // Save initial job metadata in a Redis hash
        Redis::hmset(self::JOB_STATUS_PREFIX . $jobId, [
            'status' => 'queued',
            'attempts' => 0,
            'class' => $class,
            'method' => $method,
            'parameters' => json_encode($parameters),
            'priority' => $priority,
            'run_at' => $runAt
        ]);

        Logger::info("Job queued", ['class' => $class, 'method' => $method, 'priority' => $priority, 'job_id' => $jobId]);
    }

    /**
     * Process the next job from the Redis sorted set based on priority.
     */
    public static function processNextJob()
    {
        // Fetch the job with the highest priority (lowest score)
        $jobData = Redis::zpopmin(self::JOB_QUEUE);

        if ($jobData) {
            $jobArray = json_decode(key($jobData), true);
            $jobId = $jobArray['id'];

            // Check the status of the job before processing
            $status = Redis::hget(self::JOB_STATUS_PREFIX . $jobId, 'status');

            // Skip job if status is 'canceled', 'completed', or 'failed'
            if (in_array($status, ['canceled', 'completed', 'failed'])) {
                Logger::info("Skipping job with status {$status}", ['job_id' => $jobId]);
                return;
            }

            // Check if job is delayed
            if (isset($jobArray['run_at']) && $jobArray['run_at'] > now()->timestamp) {
                // Reinsert if it's not time to run yet
                $priorityScore = $jobArray['priority'] > 0 ? 0 : 1;
                Redis::zadd(self::JOB_QUEUE, $priorityScore, json_encode($jobArray));
                return;
            }

            $job = new BackgroundJob(
                $jobArray['id'],
                $jobArray['class'],
                $jobArray['method'],
                $jobArray['parameters'],
                $jobArray['priority'],
                $jobArray['attempts']
            );

            self::executeJob($job);
        }
    }

    /**
     * Execute the given job and handle retries, status updates, and logging.
     */
    private static function executeJob(BackgroundJob $job)
    {
        Redis::hset(self::JOB_STATUS_PREFIX . $job->id, 'status', 'running');

        try {
            // Ensure the class and method are valid
            if (!class_exists($job->class)) {
                throw new \Exception("Class {$job->class} does not exist.");
            }

            $instance = app($job->class);

            if (!method_exists($instance, $job->method)) {
                throw new \Exception("Method {$job->method} does not exist on class {$job->class}.");
            }

            // Execute the method
            $result = call_user_func_array([$instance, $job->method], $job->parameters);
            Redis::hset(self::JOB_STATUS_PREFIX . $job->id, 'status', 'completed');
            Logger::info("Job completed successfully", ['class' => $job->class, 'method' => $job->method, 'job_id' => $job->id, 'result' => $result]);

        } catch (\Exception $e) {
            // Handle retry if maximum attempts not reached
            if ($job->attempts < config('background_jobs.max_retries', 3)) {
                $job->attempts++;
                Redis::hset(self::JOB_STATUS_PREFIX . $job->id, 'attempts', $job->attempts);
                self::requeueJob($job);
            } else {
                Redis::hset(self::JOB_STATUS_PREFIX . $job->id, 'status', 'failed');

                Logger::error("Job failed after max retries", [
                    'job_id' => $job->id,
                    'class' => $job->class,
                    'method' => $job->method,
                    'parameters' => $job->parameters,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Requeue a failed job with updated attempts count.
     */
    private static function requeueJob(BackgroundJob $job)
    {
        $priorityScore = $job->priority > 0 ? 0 : 1;
        Redis::zadd(self::JOB_QUEUE, $priorityScore, json_encode($job->toArray()));
        Logger::info("Job requeued", ['class' => $job->class, 'method' => $job->method, 'attempts' => $job->attempts, 'job_id' => $job->id]);
    }

    /**
     * Cancel job
     */
    public static function cancelJob($jobId)
    {
        Redis::hset(self::JOB_STATUS_PREFIX . $jobId, 'status', 'canceled');
        Logger::info("Job canceled", ['job_id' => $jobId]);
    }

}
