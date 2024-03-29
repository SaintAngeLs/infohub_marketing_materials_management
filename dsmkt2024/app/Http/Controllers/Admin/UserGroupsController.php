<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\UsersGroup;
use Illuminate\Http\Request;

/**
 * Consentions
 */
class UserGroupsController extends Controller
{
    public function index()
    {
        // $users = User::all();
        $userGroups = UsersGroup::all();
        return view('admin.groups.index', compact('userGroups'));
    }
    public function create()
    {
        // $user = User::all();
        return view('admin.groups.create');
    }

    public function edit($id)
    {
        $userGroup = UsersGroup::findOrFail($id);
        return view('admin.groups.edit', compact('userGroup'));
    }

    // Dodanie dowej concesji nie grupy!
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'address' => 'required|string|max:255',
    //         'code' => 'required|string|max:10',
    //         'city' => 'required|string|max:100',
    //         'phone' => 'required|string|max:12',
    //         'fax' => 'nullable|string|max:12',
    //     ]);

    //     Branch::create($request->all());

    //     return redirect()->route('concessions')->with('success', 'Grupa została dodana pomyślnie.');
    // }

}
