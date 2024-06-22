<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    protected $fillable = [
        'name',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
