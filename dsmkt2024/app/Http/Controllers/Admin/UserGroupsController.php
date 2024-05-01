<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\IStatistics;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use App\Models\UsersGroup;
use Illuminate\Http\Request;

class UserGroupsController extends Controller
{
    protected $statisticsService;

    public function __construct(IStatistics $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $userGroups = UsersGroup::all();
        return view('admin.groups.index', compact('userGroups'));
    }
    public function create()
    {
        $userGroup = new UsersGroup();
        return view('admin.groups.create', compact('userGroup'));
    }

    public function edit($id)
    {
        $userGroup = UsersGroup::findOrFail($id);
        return view('admin.groups.edit', compact('userGroup'));
    }

    public function store(Request $request)
    {
        \Log::info("The store request in the UserGroupsController is", $request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'menu_permissions' => 'required|array',
        ]);

        $userGroup = UsersGroup::create([
            'name' => $request->name
        ]);

        $permissionsIds = array_keys($request->menu_permissions);

        $userGroup->permissions()->attach($permissionsIds);

        $this->statisticsService->logUserActivity(auth()->id(), [
            'uri' => $request->path(),
            'post_string' => $request->except('_token'),
            'query_string' => $request->getQueryString(),
        ]);

        return redirect()->route('menu.users.groups')->with('success', 'Grupa została dodana pomyślnie.');
    }

}
