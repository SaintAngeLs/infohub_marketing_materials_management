<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Consentions
 */
class ConcessionsController extends Controller
{
    public function index()
    {
        return view('admin.concessions.index');
    }
    public function create()
    {
        return view('admin.concessions.create');
    }
}
