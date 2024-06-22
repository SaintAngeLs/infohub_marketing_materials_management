<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'menu_item_user';
    protected $primaryKey = ['menu_item_id', 'user_id'];
    public $incrementing = false;
    protected $fillable = [
        'menu_item_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
