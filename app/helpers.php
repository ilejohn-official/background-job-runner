<?php

use App\Services\BackgroundJobRunner;
use Illuminate\Support\Facades\Redis;

if (!function_exists('runBackgroundJob')) {
    /**
     * Execute a background job by queuing it in Redis and triggering a single instance of the background process.
     *
     * @param string $class The class to be executed.
     * @param string $method The method within the class to be executed.
     * @param array $parameters Parameters to pass to the method.
     * @param int $priority Priority of the job (0 for low, 1 for high).
     * @return void
     */
    function runBackgroundJob($class, $method, $parameters = [], $priority = 0, $delay = 0)
    {
        // Queue the job in Redis using the BackgroundJobRunner service
        BackgroundJobRunner::queueJob($class, $method, $parameters, $priority, $delay);

        // Check for an active background job processor (e.g., lock in Redis)
        $lockKey = 'background_job_processor_running';
        
        if (!Redis::exists($lockKey)) {
            // Set the lock in Redis with an expiration time (e.g., 10 seconds)
            Redis::setex($lockKey, 10, true);

            // Command to run the Laravel background job processor
            $command = "php " . base_path('artisan') . " background-jobs:process > /dev/null 2>&1 &";

            // Determine the operating system and execute command accordingly
            if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
                // Windows background process execution
                pclose(popen("start /B " . $command, "r"));
            } else {
                // Unix-based background process execution
                exec($command);
            }
        }
    }
}
