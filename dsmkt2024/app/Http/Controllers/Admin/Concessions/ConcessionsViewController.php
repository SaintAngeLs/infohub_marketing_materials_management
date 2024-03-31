<?php

namespace App\Http\Controllers\Admin\Concessions;

use App\Http\Controllers\Controller;
use App\Models\Branch;

class ConcessionsViewController extends Controller
{
    public function index()
    {
        $concessions = Branch::all();
        return view('admin.concessions.index', compact('concessions'));
    }

    public function create()
    {
        return view('admin.concessions.create');
    }

    public function edit($id)
    {
        $concession = Branch::findOrFail($id);
        return view('admin.concessions.edit', compact('concession'));
    }
}
