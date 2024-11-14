<?php

namespace App\SampleJobs;

use Illuminate\Support\Facades\Log;

class JobB
{
    public function handle(string $str)
    {
        Log::info($str);
    }
}
