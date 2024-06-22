<?php

namespace App\Http\Controllers;

use App\Services\JobService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function listFailedJobs()
    {
        $failedJobs = $this->jobService->findFailedJobs();
        return response()->json($failedJobs);
    }

    public function retryJob(Request $request, $jobId)
    {
        $this->jobService->retryJob($jobId);
        return response()->json(['message' => 'Job retried successfully']);
    }

    public function deleteFailedJob(Request $request, $jobId)
    {
        $this->jobService->deleteFailedJob($jobId);
        return response()->json(['message' => 'Failed job deleted successfully']);
    }
}
