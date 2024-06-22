<?php

namespace App\Contracts;

interface IConcessionService
{
    public function createConcession(array $data);
    public function updateConcession($id, array $data);
}
