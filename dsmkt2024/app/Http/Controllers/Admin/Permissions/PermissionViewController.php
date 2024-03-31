<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Controller;

class PermissionViewController extends Controller
{
    public function index()
    {
        return view('admin.autos.index');
    }
}
