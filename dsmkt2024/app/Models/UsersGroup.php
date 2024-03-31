<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersGroup extends Model
{
    protected $table = 'users_groups';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'users_groups_id');
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'user_group_menu_item',
        'user_group_id', 'menu_item_id');
    }
}

