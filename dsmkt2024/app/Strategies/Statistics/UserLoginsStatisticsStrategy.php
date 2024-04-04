<?php
namespace App\Strategies;

use App\Strategies\StatisticsStrategy;
use App\Models\UserAuthentication;

class UserLoginsStatisticsStrategy implements StatisticsStrategy
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
        return UserAuthentication::query()
            ->whereBetween('fingerprint', [$this->from, $this->to]);
    }

    public function headings(): array
    {
        return ["Date", "IP Address", "User ID"];
    }
}
