<?php

namespace App\SampleJobs;

use Exception;

class FailingJob
{
    public function run(string $str)
    {
       throw new Exception('Failed job');
    }
}
