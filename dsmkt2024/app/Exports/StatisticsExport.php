<?php

namespace App\Exports;

use App\Strategies\StatisticsStrategy;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $strategy;

    public function __construct(StatisticsStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function query()
    {
        return $this->strategy->query();
    }

    public function headings(): array
    {
        // Delegate the headings to the strategy
        return $this->strategy->headings();
    }
}
