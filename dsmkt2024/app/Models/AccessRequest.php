<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'access_requests';

    // Indicates if the model should be timestamped.
    public $timestamps = false;

    // The attributes that are mass assignable.
    protected $fillable = [
        'company_name',
        'name',
        'surname',
        'phone',
        'email',
        'status',
        'accepted_by',
        'refused_by',
        'refused_comment',
        'created_at',
        // 'fingerprint', // Usually, you don't need to fill this manually
    ];

    // The attributes that should be cast.
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that accepted the access request.
     */
    public function acceptor()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    /**
     * Get the user that refused the access request.
     */
    public function refuser()
    {
        return $this->belongsTo(User::class, 'refused_by');
    }
}
