<?php

namespace App\Services;

use App\Contracts\IStatistics;
use App\Contracts\StatisticsInterface;
use App\Models\UserLog;
use App\Models\UserDownload;
use App\Models\UserViewItem;

class StatisticsService implements IStatistics
{
    public function logUserActivity($userId, $data)
    {
        UserLog::create([
            'user_id' => $userId,
            'uri' => $data['uri'] ?? null,
            'post_string' => $data['post_string'] ?? null,
            'query_string' => $data['query_string'] ?? null,
            'file_string' => $data['file_string'] ?? null,
            'ip' => request()->ip(),
        ]);
    }

    public function logDownload($userId, $fileId)
    {
        UserDownload::create([
            'user_id' => $userId,
            'file_id' => $fileId,
            'user_ip' => request()->ip(),
        ]);
    }

    public function logViewItem($userId, $menuItemId)
    {
        UserViewItem::create([
            'user_id' => $userId,
            'menu_item_id' => $menuItemId,
            'user_ip' => request()->ip(),
        ]);
    }
}
