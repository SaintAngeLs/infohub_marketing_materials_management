<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'code',
        'city',
        'phone',
        'fax'
    ];
    public function users()
    {
        return $this->hasMany(User::class, 'branch_id');
    }

}
