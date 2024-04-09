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
    public function getAutoById($id)
    {
        return Auto::find($id);
    }

    public function updateAuto($id, array $data)
    {
        $auto = $this->getAutoById($id);
        if ($auto) {
            $auto->update($data);
            return $auto;
        }

        return null;
    }
}
