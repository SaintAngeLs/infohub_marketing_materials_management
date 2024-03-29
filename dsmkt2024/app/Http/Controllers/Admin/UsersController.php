<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsersGroup;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $userGroups = UsersGroup::all();
        return view('admin.users.index', compact('userGroups'));
    }

    public function create()
    {
        $userGroups = UsersGroup::all();
        return view('admin.users.create', compact('userGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_groups_id' => 'nullable|exists:users_groups,id',
            'branch_id' => 'nullable|exists:branches,id',
            'name' => 'nullable|string|max:100',
            'surname' => 'nullable|string|max:100',
            'email' => 'nullable|string|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'active' => 'required|boolean',
        ]);

        $password = $request->input('password') ? bcrypt($request->input('password')) : null;

        $user = User::create([
            'users_groups_id' => $request->input('users_groups_id'),
            'branch_id' => $request->input('branch_id'),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => $password,
            'active' => $request->input('active'),
            'password_last_changed' => now(),
            'last_login' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

}
