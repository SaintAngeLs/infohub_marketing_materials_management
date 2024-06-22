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
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
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
