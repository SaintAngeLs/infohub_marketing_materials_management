<?php

namespace App\Strategies;

use App\Models\UserViewItem;
use App\Strategies\StatisticsStrategy;

class MenuEntriesViewsStatisticsStrategy implements StatisticsStrategy
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return UserViewItem::query()
            ->whereBetween('created_at', [$this->from, $this->to])
            ->with('menuItem');
    }

    public function headings(): array
    {
        return ["Menu Item ID", "Views"];
    }
}
