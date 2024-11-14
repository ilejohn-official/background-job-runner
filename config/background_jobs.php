<?php

return [
    'max_retries' => env('BAKGROUND_JOB_MAX_RETRIES', 3), // Maximum retry attempts for failed jobs
    'approved_classes' => [
        \App\SampleJobs\SampleJob::class,
        \App\SampleJobs\JobA::class,
        \App\SampleJobs\JobB::class,
        \App\SampleJobs\FailingJob::class,
        // Add other approved job classes here
    ],
];
