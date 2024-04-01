<?php

namespace App\Http\Controllers\Admin\Applications;

use App\Strategies\UserCreation\CreateUserWithoutPassword;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Contracts\IApplication;
use App\Models\AccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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

    public function acceptApplication($id, Request $request)
    {
        $application = AccessRequest::find($id);
        if ($application) {

            $application->update([
                'status' => 1, // Accepted
                'accepted_by' => Auth::id(),
            ]);

            $userData = [
                'name' => $application->name,
                'surname' => $application->surname,
                'email' => $application->email,
                'branch_id' => $request->branch_id ?? null,
                'users_groups_id' => $request->users_groups_id ?? null,
            ];

            Log::info('Creating the new user');
            $userCreationStrategy = new CreateUserWithoutPassword();
            $userCreationStrategy->createUser($userData);

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
            'status' => 'required|in:0,1,2',
            'refused_comment' => 'nullable|string',
        ]);

        $application->update([
            'status' => $validated['status'],
            'accepted_by' => $validated['status'] == 1 ? Auth::id() : null,
            'refused_by' => $validated['status'] == 2 ? Auth::id() : null,
            'refused_comment' => $validated['status'] == 2 ? $validated['refused_comment'] : null,
        ]);

        if ($validated['status'] == 1) {
            $userData = [
                'name' => $application->name,
                'surname' => $application->surname,
                'email' => $application->email,
                'phone' => $application->phone,
                'branch_id' => $request->branch_id ?? null,
                'users_groups_id' => $request->users_groups_id ?? null,
            ];

            Log::info('Attempting to create a new user from application acceptance', ['email' => $userData['email']]);
            $userCreationStrategy = new CreateUserWithoutPassword();
            $user = $userCreationStrategy->createUser($userData);
            Log::info('New user created successfully', ['user_id' => $user->id]);
        }

        return redirect()->route('menu.users.applications.view')->with('success', 'Application status updated successfully.');
    }

}
