<?php

namespace App\Http\Controllers\Admin\Statistics;

use App\Exports\StatisticsExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAuthentication;
use App\Strategies\Statistics\MenuEntriesViewsStatisticsStrategy;
use App\Strategies\Statistics\UserDownloadsStatisticsStrategy;
use App\Strategies\Statistics\UserLoginsStatisticsStrategy;
use Illuminate\Http\Request;
use App\Models\UserLog;
use App\Models\UserDownload;
use App\Models\UserViewItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;


class StatisticsManagementController extends Controller
{
    public function showMenuEntries(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth()->startOfDay()->format('Y-m-d H:i:s'));
        $to = $request->input('to', Carbon::now()->endOfDay()->format('Y-m-d H:i:s'));

        $menuViewCounts = UserViewItem::whereBetween('created_at', [$from, $to])
                                    ->groupBy('menu_item_id')
                                    ->selectRaw('menu_item_id, COUNT(*) as views')
                                    ->with('menuItem')
                                    ->get();

        $totalViews = $menuViewCounts->sum('views');

        $formattedFrom = Carbon::parse($from)->format('d.m.Y');
        $formattedTo = Carbon::parse($to)->format('d.m.Y');

        return view('admin.statistics.entries', compact('menuViewCounts', 'totalViews', 'formattedFrom', 'formattedTo'));
    }


    public function menuItemDetails($menuItemId)
    {
        $userViews = UserViewItem::where('menu_item_id', $menuItemId)
                        ->with('user')
                        ->get();

        return view('admin.statistics.menu-item-details', compact('userViews'));
    }


    public function showDownloads(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $downloads = UserDownload::whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $from)->startOfDay(), Carbon::createFromFormat('Y-m-d', $to)->endOfDay()])
                        ->with('file.menuItem')
                        ->get();


        $aggregatedDownloads = $downloads->groupBy('file_id')
            ->map(function ($items, $fileId) {
                return [
                    'file_id' => $fileId,
                    'file' => $items->first()->file->name,
                    'menuItem' => $items->first()->file->menuItem->name ?? 'N/A',
                    'count' => $items->count(),
                ];
            })->values()->all();

        $totalDownloads = $downloads->count();

        return view('admin.statistics.downloads', compact('aggregatedDownloads', 'totalDownloads', 'from', 'to'));
    }

    public function fileDetails($fileId)
    {
        $downloads = UserDownload::where('file_id', $fileId)
                        ->join('users', 'users_downloads.user_id', '=', 'users.id')
                        ->get(['users.name', 'users.surname', 'users_downloads.user_ip', 'users_downloads.created_at']);

        $fileName = UserDownload::where('file_id', $fileId)->first()->file->name ?? 'Unknown File';
        return view('admin.statistics.file-downloads-detail', compact('downloads', 'fileName'));
    }


    public function showLogins(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        $logins = UserAuthentication::whereBetween('fingerprint', [$from, $to])
                    ->with('user')
                    ->get()
                    ->groupBy('user_id')
                    ->map(function ($logins) {
                        $firstLogin = $logins->first();
                        if ($firstLogin->user) {
                            return [
                                'name' => $firstLogin->user->name,
                                'user_id' => $firstLogin->user->id,
                                'surname' => $firstLogin->user->surname,
                                'user_group' => $firstLogin->user->user_group ?? 'N/A',
                                'login_count' => $logins->count(),
                            ];
                        } else {
                            return [
                                'name' => 'Unknown',
                                'surname' => '',
                                'user_group' => 'N/A',
                                'login_count' => $logins->count(),
                            ];
                        }
                    });

        $totalLogins = $logins->sum('login_count');

        return view('admin.statistics.logins', compact('logins', 'totalLogins', 'from', 'to'));
    }

    public function userLoginsDetails($userId)
    {
        $userLogins = UserAuthentication::where('user_id', $userId)
                            ->with('user')
                            ->orderBy('fingerprint', 'desc')
                            ->get();

        $user = User::find($userId);

        return view('admin.statistics.user-logins-detail', compact('userLogins', 'user'));
    }

    public function downloadExcel(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth()->toDateString());
        $to = $request->input('to', Carbon::now()->toDateString());
        $type = $request->input('type'); // 'logins', 'downloads', 'entries'

        $strategy = null;

        switch ($type) {
            case 'logins':
                $strategy = new UserLoginsStatisticsStrategy($from, $to);
                break;
            case 'downloads':
                $strategy = new UserDownloadsStatisticsStrategy($from, $to);
                break;
            case 'entries':
                $strategy = new MenuEntriesViewsStatisticsStrategy($from, $to);
                break;
            default:
                Log::error("Invalid statistics type provided: {$type}");

                throw new InvalidArgumentException("Invalid statistics type provided.");
        }

        if ($strategy) {
            $fileName = "statistics_{$type}_{$from}_to_{$to}.xlsx";
            return Excel::download(new StatisticsExport($strategy), $fileName);
        }
    }
}
