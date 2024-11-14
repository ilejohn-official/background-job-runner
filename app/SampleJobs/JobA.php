<?php

namespace App\SampleJobs;

use Illuminate\Support\Facades\Log;

class JobA
{
    public function handle(string $str)
    {
        Log::debug($str);
    }
}
