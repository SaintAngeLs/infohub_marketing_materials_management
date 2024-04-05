<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthentication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'fingerprint',
    ];

    public $timestamps = false;

    protected $dates = ['fingerprint'];

    protected $table = 'user_authentication';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
