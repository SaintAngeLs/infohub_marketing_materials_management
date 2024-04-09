<?php

namespace App\Contracts;

interface IStatistics
{
    public function logUserActivity($userId, $data);
    public function logDownload($userId, $fileId);
    public function logViewItem($userId, $menuItemId);
}
