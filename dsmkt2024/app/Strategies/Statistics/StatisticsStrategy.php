<?php

namespace App\Strategies\Statistics;

interface StatisticsStrategy
{
    public function query();
    public function headings(): array;
}
