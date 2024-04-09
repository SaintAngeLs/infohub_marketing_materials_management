<?php

namespace App\Http\Controllers\Admin\Autos;

use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use App\Contracts\IAutoService;
use Illuminate\Http\Request;

class AutosManagementController extends Controller
{
    protected $autoService;
    protected $statisticsService;

    public function __construct(IAutoService $autoService, IStatistics $statisticsService)
    {
        $this->autoService = $autoService;
        $this->statisticsService = $statisticsService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $this->autoService->createAuto($validated);

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

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

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

        return redirect()->route('menu.autos.index')->with('success', 'Auto updated successfully.');
    }
}
