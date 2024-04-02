<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'content',
        'status',
        'error',
        'sent_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
