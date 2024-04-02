<?php

namespace App\Http\Controllers\Admin\Statistics;

use App\Exports\StatisticsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLog;
use App\Models\UserDownload;
use App\Models\UserViewItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsManagementController extends Controller
{
    public function showEntries(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $entries = UserLog::whereBetween('created_at', [$from, $to])->get();

        return view('admin.statistics.entries', compact('entries', 'from', 'to'));
    }

    public function showDownloads(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $downloads = UserDownload::whereBetween('created_at', [$from, $to])->get();

        return view('admin.statistics.downloads', compact('downloads', 'from', 'to'));
    }

    public function showLogins(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth());
        $to = $request->input('to', Carbon::now());

        $logins = UserLog::where('action', 'login')->whereBetween('created_at', [$from, $to])->get();

        return view('admin.statistics.logins', compact('logins', 'from', 'to'));
    }

    public function downloadExcel(Request $request)
    {
        $from = $request->input('from', Carbon::now()->subMonth()->toDateString());
        $to = $request->input('to', Carbon::now()->toDateString());

        $fileName = "statistics_{$from}_to_{$to}.xlsx";
        return Excel::download(new StatisticsExport($from, $to), $fileName);
    }

}
