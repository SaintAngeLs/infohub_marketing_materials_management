<?php

namespace App\Strategies\Statistics;

use App\Models\UserDownload;
use App\Strategies\Statistics\StatisticsStrategy;

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
            ->join('files', 'users_downloads.file_id', '=', 'files.id')
            ->join('users', 'users_downloads.user_id', '=', 'users.id')
            ->whereBetween('users_downloads.created_at', [$this->from, $this->to])
            ->select(
                'users_downloads.created_at',
                'files.name as file_name',
                'users.name as user_name'
            );
    }

    public function headings(): array
    {
        return ["Date", "File Name", "User Name"]; // Adjust the heading for user name
    }
}
