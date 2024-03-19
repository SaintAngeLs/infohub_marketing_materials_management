<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsentionController extends Controller
{
    public function index()
    {
        return view('consentions.index');
    }
}
