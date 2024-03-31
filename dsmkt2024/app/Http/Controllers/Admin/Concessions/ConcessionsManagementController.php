<?php

namespace App\Http\Controllers\Admin\Concessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ConcessionService;

class ConcessionsManagementController extends Controller
{
    protected $concessionService;

    public function __construct(ConcessionService $concessionService)
    {
        $this->concessionService = $concessionService;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
            'fax' => 'nullable|string|max:12',
        ]);

        $this->concessionService->createConcession($validatedData);

        return redirect()->route('menu.concessions.index')->with('success', 'Koncesja została dodana pomyślnie.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:12',
            'email' => 'required|string|email|max:255',
        ]);

        $this->concessionService->updateConcession($id, $validatedData);

        return redirect()->route('menu.concessions.index')->with('success', 'Koncesja została zaktualizowana pomyślnie.');
    }
}
