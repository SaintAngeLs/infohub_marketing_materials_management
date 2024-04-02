<?php

namespace App\Exports;

use App\Models\UserLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StatisticsExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $from;
    protected $to;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return UserLog::query()
            ->whereBetween('created_at', [$this->from, $this->to]);
    }

    public function headings(): array
    {
        return ["ID", "User ID", "URI", "Action", "Created At"];
    }
}
