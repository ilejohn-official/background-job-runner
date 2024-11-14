<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger running a job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Running Job..");

        runBackgroundJob('\App\Jobs\JobA', 'testJob', ['This is the first', 'test of our helper method !!!!!!!!!!!!'], 0, 5);
    }
}
