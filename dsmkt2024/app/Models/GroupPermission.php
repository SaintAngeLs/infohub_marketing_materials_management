<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Model;

class GroupPermission extends Model
{
    protected $table = 'user_group_menu_item';
    protected $fillable = [
        'menu_item_id',
        'user_group_id',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function userGroup()
    {
        return $this->belongsTo(UsersGroup::class, 'user_group_id');
    }
}
