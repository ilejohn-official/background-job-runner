<?php

namespace App\SampleJobs;

use Illuminate\Support\Facades\Log;

class SampleJob
{
    public function process(string $a, string $b)
    {
        Log::debug("$a $b");
    }
}
