<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDownload extends Model
{
    protected $fillable = [
        'user_id',
        'file_id', 
        'user_ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
