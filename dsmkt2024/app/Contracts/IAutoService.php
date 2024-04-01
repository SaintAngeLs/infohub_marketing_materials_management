<?php

namespace App\Contracts;

interface IAutoService
{
    public function getAllAutos();
    public function createAuto(array $data);
}
