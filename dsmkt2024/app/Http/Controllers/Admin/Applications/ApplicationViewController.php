<?php

namespace App\Http\Controllers\Admin\Applications;

use App\Http\Controllers\Controller;
use App\Contracts\IApplication;
use App\Models\AccessRequest;

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

    public function showDetails($id)
    {
        $application = AccessRequest::findOrFail($id);
        return view('admin.applications.edit', compact('application'));
    }


    // Add methods for viewing individual application details as needed
}
