<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        Log::info('Request in teh update function: ' . $request->all());
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);


        $user = $request->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log the password update
        Log::info('Password updated for user: ' . $user->email);

        return back()->with('status', 'password-updated');
    }
}
