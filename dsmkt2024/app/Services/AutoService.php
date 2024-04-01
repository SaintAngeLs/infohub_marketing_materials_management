<?php

namespace App\Services;

use App\Contracts\IAutoService;
use App\Models\Auto;

class AutoService implements IAutoService
{
    public function getAllAutos()
    {
        return Auto::all();
    }

    public function createAuto(array $data)
    {
        return Auto::create($data);
    }

    // Implement other methods defined in the interface
}
