<?php

namespace App\Http\Controllers\Admin\Applications;

use Illuminate\Support\Facades\Auth;
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
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Your access request has been submitted successfully.');
    }

    public function acceptApplication($id)
    {
        $application = AccessRequest::find($id);
        if ($application) {

            $application->update([
                'status' => 1, // Accepted
                'accepted_by' => Auth::id(),
            ]);
            return redirect()->route('menu.users.applications.view')->with('success', 'Application accepted successfully.');
        }
        return back()->withErrors(['error' => 'Application not found.']);
    }

    public function rejectApplication($id, Request $request)
    {
        $application = AccessRequest::find($id);
        if ($application) {
            $application->update([
                'status' => 2, // Rejected
                'refused_by' => Auth::id(),
                'refused_comment' => $request->input('refused_comment'),
            ]);
            return redirect()->route('menu.users.applications.view')->with('success', 'Application rejected successfully.');
        }
        return back()->withErrors(['error' => 'Application not found.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $application = AccessRequest::find($id);
        if (!$application) {
            return back()->withErrors(['error' => 'Application not found.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:1,2',
            'refused_comment' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'accepted_by' => $validated['status'] == 1 ? Auth::id(): null,
            'refused_by' => $validated['status'] == 2 ? Auth::id() : null,
            'refused_comment' => $validated['status'] == 2 ? $validated['refused_comment'] : null,
        ];

        $application->update($updateData);

        return redirect()->route('menu.users.applications.view')->with('success', 'Application status updated successfully.');
    }

}
