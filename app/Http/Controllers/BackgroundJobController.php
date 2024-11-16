<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\BackgroundJobRunner;

class BackgroundJobController extends Controller
{
    /**
     * Display the list of background jobs with their statuses.
     */
    public function index()
    {
        $jobs = DashboardService::getAllJobStatuses();
        return view('background_jobs.index', compact('jobs'));
    }

    /**
     * Show logs for a specific job.
     */
    public function showLogs($id)
    {
        $logs = BackgroundJobRunner::getJobLogs($id);
        return view('background_jobs.logs', compact('logs', 'id'));
    }

    /**
     * Cancel a specific job.
     */
    public function cancelJob($id)
    {
        BackgroundJobRunner::cancelJob($id);
        return redirect()->route('background-jobs.index')->with('status', 'Job canceled successfully.');
    }
}
