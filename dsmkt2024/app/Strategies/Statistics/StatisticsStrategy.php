<?php

namespace App\Strategies;

interface StatisticsStrategy
{
    public function query();
    public function headings(): array;
}
