<?php

namespace App\Services;

use App\Contracts\IStatistics;
use App\Contracts\StatisticsInterface;
use App\Models\UserLog;
use App\Models\UserDownload;
use App\Models\UserViewItem;
use Illuminate\Http\Request;

class StatisticsService implements IStatistics
{
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function logUserActivity($userId, $data)
    {
        $queryString = $this->request->getQueryString();
        $postData = $this->request->except(['_token', 'file']);
        $postString = json_encode($postData);

        $files = $this->request->allFiles();
        $fileDetails = [];
        foreach ($files as $file) {
            $fileDetails[] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ];
        }
        $fileString = json_encode($fileDetails);

        UserLog::create([
            'user_id' => $userId,
            'uri' => $data['uri'] ?? null,
            'post_string' => $postString,
            'query_string' => $queryString,
            'file_string' => $fileString,
            'ip' => $this->request->ip(),
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
