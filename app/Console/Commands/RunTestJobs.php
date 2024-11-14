<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunTestJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-test-jobs {case=a}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger running test case jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $case = $this->argument('case');

        $this->info("Running test case $case...");

        switch ($case) {
            case 'a':
                // Queue a job with no delay or priority, and confirm it executes successfully.
                runBackgroundJob(\App\SampleJobs\SampleJob::class, 'process', ['This is the first', 'test of our helper method !!!!!!!!!!!!']);
                break;
            case 'b':
                // Queue two jobs with different priorities and verify that the high-priority job is executed first.
                runBackgroundJob(\App\SampleJobs\JobA::class, 'handle', ['Parameter A'], 0);
                runBackgroundJob(\App\SampleJobs\JobB::class, 'handle', ['Parameter B'], 1);
                break;
            case 'c':
                // Queue a job with a delay and verify it is executed only after the delay period.
                runBackgroundJob(\App\SampleJobs\JobA::class, 'handle', ['Delayed Job']);
                break;
            case 'd':
                // Queue a job that throws an exception to test retry handling.
                runBackgroundJob(\App\SampleJobs\FailingJob::class, 'run', ['Failed Job']);
                break;
        }

        $this->info("Test case $case ran!");
    }
}
