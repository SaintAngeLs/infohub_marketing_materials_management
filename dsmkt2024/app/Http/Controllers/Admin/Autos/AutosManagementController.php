<?php

namespace App\Http\Controllers\Admin\Autos;

use App\Http\Controllers\Controller;
use App\Contracts\IAutoService;
use Illuminate\Http\Request;

class AutosManagementController extends Controller
{
    protected $autoService;

    public function __construct(IAutoService $autoService)
    {
        $this->autoService = $autoService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $this->autoService->createAuto($validated);

        return redirect()->route('menu.autos.index')->with('success', 'Auto created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);

        $auto = $this->autoService->getAutoById($id);
        if (!$auto) {
            return back()->with('error', 'Samochód nie został znaleziony.');
        }

        $this->autoService->updateAuto($id, $validated);

        return redirect()->route('menu.autos.index')->with('success', 'Auto updated successfully.');
    }
}
