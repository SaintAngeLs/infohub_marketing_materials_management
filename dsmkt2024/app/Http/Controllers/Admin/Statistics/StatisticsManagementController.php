<?php

namespace App\Http\Controllers\Admin\Statistics;

use App\Exports\StatisticsExport;
use App\Http\Controllers\Controller;
use App\Models\UserAuthentication;
use App\Strategies\MenuEntriesViewsStatisticsStrategy;
use App\Strategies\UserDownloadsStatisticsStrategy;
use App\Strategies\UserLoginsStatisticsStrategy;
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
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $menuViewCounts = UserViewItem::whereBetween('created_at', [$from, $to])
                                    ->groupBy('menu_item_id')
                                    ->selectRaw('menu_item_id, COUNT(*) as views')
                                    ->with('menuItem')
                                    ->get();

        $totalViews = $menuViewCounts->sum('views');

        return view('admin.statistics.entries', compact('menuViewCounts', 'totalViews', 'from', 'to'));
    }

    public function showDownloads(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $downloads = UserDownload::whereBetween('created_at', [$from, $to])->with('file')->get();

        return view('admin.statistics.downloads', compact('downloads', 'from', 'to'));
    }

    public function showLogins(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $logins = UserAuthentication::whereBetween('fingerprint', [$from, $to])
                    ->with('user')
                    ->get();

        return view('admin.statistics.logins', compact('logins', 'from', 'to'));
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
