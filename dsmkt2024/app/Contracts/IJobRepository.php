<?php

namespace App\Contracts;

interface IJobRepository
{
    public function findFailedJobs();
    public function findJobBatches();
    public function retryJob($jobId);
    public function deleteFailedJob($jobId);
}
