<?php

namespace App\Http\Controllers\Admin\Autos;

use App\Http\Controllers\Controller;
use App\Contracts\IAutoService;

class AutosViewController extends Controller
{
    protected $autoService;

    public function __construct(IAutoService $autoService)
    {
        $this->autoService = $autoService;
    }

    public function index()
    {
        $autos = $this->autoService->getAllAutos();
        return view('admin.autos.index', compact('autos'));
    }
}
