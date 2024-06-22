<?php

namespace App\Contracts;

interface IAutoService
{
    public function getAllAutos();
    public function createAuto(array $data);
    public function getAutoById($id);
    public function updateAuto($id, array $data);
}
