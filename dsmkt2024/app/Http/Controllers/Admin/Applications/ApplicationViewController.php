<?php

namespace App\Http\Controllers\Admin\Applications;

use App\Http\Controllers\Controller;
use App\Contracts\IApplication;

class ApplicationViewController extends Controller
{
    protected $applicationService;

    public function __construct(IApplication $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function index()
    {
        $applications = $this->applicationService->getAllApplications();
        return view('admin.applications.index', compact('applications'));
    }

    // Add methods for viewing individual application details as needed
}
