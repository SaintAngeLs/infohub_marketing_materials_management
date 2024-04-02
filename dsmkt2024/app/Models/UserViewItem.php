<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Model;

class UserViewItem extends Model
{
    protected $fillable = [
        'user_id',
        'menu_item_id',
        'user_ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
