<?php

namespace App\Models\MenuItems;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UsersGroup;
use Fureev\Trees\Config\Base;
use Fureev\Trees\Contracts\TreeConfigurable;
use Illuminate\Database\Eloquent\Model;
use Fureev\Trees\NestedSetTrait;

class MenuItem extends Model
{
    use NestedSetTrait;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'owner_id',
        'user_id',
        'banner_id',
        'position',
        'start',
        'end',
        'status',
        'archived',
        'archived_at',
        'archived_by',
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];


    protected $treeConfig = [
        'parent' => 'parent_id',
        'left'   => 'left_edge',
        'right'  => 'right_edge',
        'depth'  => 'depth_column',
    ];

    protected static function buildTreeConfig(): Base
    {
        return new Base(true);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'menu_owners', 'menu_item_id', 'user_id');
    }


    public function files()
    {
        return $this->hasMany(\App\Models\File::class, 'menu_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'menu_item_user', 'menu_item_id', 'user_id');
    }

    public function userGroups()
    {
        return $this->belongsToMany(UsersGroup::class, 'user_group_menu_item', 'menu_item_id', 'user_group_id');
    }

    public static function getOrderedMenuItems()
    {
        // This method assumes you have a scope or a method to fetch items in their correct order
        return MenuItem::orderBy('parent_id')->orderBy('position')->get()->toTree();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function notificationPreferences()
    {
        return $this->hasMany(UserNotification::class, 'menu_item_id');
    }
}
