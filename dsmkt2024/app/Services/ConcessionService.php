<?php

namespace App\Services;

use App\Contracts\IConcessionService;
use App\Models\Branch;

class ConcessionService implements IConcessionService
{
    public function createConcession(array $data)
    {
        return Branch::create($data);
    }

    public function updateConcession($id, array $data)
    {
        $concession = Branch::findOrFail($id);
        $concession->update($data);
        return $concession;
    }
}
