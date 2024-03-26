<?php

namespace App\Models;

use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'menu_id',
        'auto_id',
        'add_by',
        'update_by',
        'display_order',
        'name',
        'path',
        'extension',
        'weight',
        'hosted',
        'start',
        'end',
        'key_words',
        'status',
        'archived',
        'archived_at',
        'archived_by',
    ];

    public function menu()
    {
        return $this->belongsTo(MenuItem::class, 'menu_id');
    }

    public function auto()
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'add_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

}
