<?php

namespace App\Console\Commands;

use App\Services\BackgroundJobRunner;
use Illuminate\Console\Command;

class ProcessBackgroundJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'background-jobs:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queued background jobs from Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Processing background jobs...");

        // Continuous loop to process jobs until stopped
        while (true) {
            BackgroundJobRunner::processNextJob();
            usleep(500000); // Small delay to avoid excessive CPU usage
        }
    }
}
