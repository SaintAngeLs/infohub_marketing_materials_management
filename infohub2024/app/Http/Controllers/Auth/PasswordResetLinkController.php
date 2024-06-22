<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Attempt to send the password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // Log detailed information about the email attempt
            Log::info("Password reset link attempt for email: {$request->email}");
            Log::info('Password reset link status: ' . $status);

            // Check the response status and log detailed information
            if ($status == Password::RESET_LINK_SENT) {
                Log::info("Password reset link sent successfully to {$request->email}");
            } else {
                Log::error("Failed to send password reset link to {$request->email}");
            }

            return $status == Password::RESET_LINK_SENT
                        ? back()->with('status', __($status))
                        : back()->withInput($request->only('email'))
                                ->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            // Log any exceptions that occur during the process
            Log::error("An error occurred while attempting to send a password reset link to {$request->email}: " . $e->getMessage());

            // Return an error response to the user
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'An unexpected error occurred. Please try again later.']);
        }
    }
}
