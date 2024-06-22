<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use App\Models\User;

interface IUserPasswordSetup
{
    public function showSetPasswordForm(Request $request, User $user);
    public function updatePassword(Request $request, User $user);
}
