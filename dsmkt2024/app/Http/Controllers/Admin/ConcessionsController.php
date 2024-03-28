<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

/**
 * Consentions
 */
class ConcessionsController extends Controller
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
            'fax' => 'nullable|string|max:12',
        ]);

        Branch::create($request->all());

        return redirect()->route('concessions')->with('success', 'Koncesja została dodana pomyślnie.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
            'email' => 'required|string|email|max:255',
        ]);

        $concession = Branch::findOrFail($id);
        $concession->update($request->all());

        return redirect()->route('menu.concessions')->with('success', 'Koncesja została zaktualizowana pomyślnie.');
    }

}
