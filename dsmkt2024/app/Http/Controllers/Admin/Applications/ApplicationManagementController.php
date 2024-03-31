<?php

namespace App\Http\Controllers\Admin\Applications;

use App\Http\Controllers\Controller;
use App\Contracts\IApplication;
use App\Models\AccessRequest;
use Illuminate\Http\Request;

class ApplicationManagementController extends Controller
{
    protected $applicationService;

    public function __construct(IApplication $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function storeAccessRequest(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        AccessRequest::create([
            'company_name' => $validated['company_name'],
            'name' => $validated['first_name'],
            'surname' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'status' => 0,
        ]);

        return redirect()->back()->with('success', 'Your access request has been submitted successfully.');
    }

    // Implement other CRUD methods similarly
}
