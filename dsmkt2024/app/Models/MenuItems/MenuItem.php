<?php

namespace App\Models\MenuItems;

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

    protected $treeConfig = [
        'parent' => 'parent_id',
        'left'   => 'left_edge',
        'right'  => 'right_edge',
        'depth'  => 'depth_column',
    ];

}
