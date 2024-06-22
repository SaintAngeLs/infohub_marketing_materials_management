<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersGroup extends Model
{
    use HasFactory;

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

    public function permissions()
    {
        return $this->belongsToMany(MenuItem::class, 'user_group_menu_item', 'user_group_id', 'menu_item_id');
    }
}

