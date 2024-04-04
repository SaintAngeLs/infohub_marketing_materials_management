<?php

namespace App\Strategies;

use App\Models\UserDownload;
use App\Strategies\StatisticsStrategy;

class UserDownloadsStatisticsStrategy implements StatisticsStrategy
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
        return UserDownload::query()
            ->whereBetween('created_at', [$this->from, $this->to])
            ->with('user', 'file');
    }

    public function headings(): array
    {
        return ["Date", "File Name", "User ID"];
    }
}
