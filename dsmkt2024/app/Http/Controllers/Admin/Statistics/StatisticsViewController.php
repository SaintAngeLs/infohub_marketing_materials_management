<?php

namespace App\Http\Controllers\Admin\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticsViewController extends Controller
{
    public function index()
    {
        return view('admin.statistics.index');
    }
}
