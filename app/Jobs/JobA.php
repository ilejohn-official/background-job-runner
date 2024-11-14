<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

class JobA
{
    public function testJob(string $a, string $b)
    {
        Log::debug($a . $b);
    }
}
