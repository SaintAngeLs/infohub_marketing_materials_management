<?php

namespace App\Services;

use App\Contracts\IStatistics;
use App\Contracts\StatisticsInterface;
use App\Models\UserLog;
use App\Models\UserDownload;
use App\Models\UserViewItem;
use Illuminate\Support\Facades\Request;

class StatisticsService implements IStatistics
{
    public function logUserActivity($userId, $data)
    {
        $queryString = $data['query_string'] ?? null;

        UserLog::create([
            'user_id' => $userId,
            'uri' => $data['uri'] ?? null,
            'post_string' => json_encode($data['post_string'] ?? []),
            'query_string' => $queryString,
            'file_string' => json_encode($data['file_string'] ?? []),
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
