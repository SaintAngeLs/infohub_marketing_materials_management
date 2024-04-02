<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\MenuItems\MenuItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'users_groups_id',
        'branch_id',
        'surname',
        'phone',
        'password_valid',
        'password_last_changed',
        'token',
        'token_time',
        'last_login',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->users_groups_id == 1;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function usersGroup()
    {
        return $this->belongsTo(UsersGroup::class, 'users_groups_id');
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_owners', 'user_id', 'menu_item_id');
    }

    public function accessibleMenuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_user', 'user_id', 'menu_item_id')->withTimestamps();
    }

    /**
     * Determine if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active == 1;
    }

    public function groupMenuItems()
    {
        return $this->hasManyThrough(
            MenuItem::class,
            UsersGroup::class,
            'id', // Foreign key on the UsersGroup table
            'id', // Foreign key on the MenuItem table
            'users_groups_id', // Local key on the users table
            'id' // Local key on the UsersGroup table
        );
    }

    public function getAllPermissionsAttribute()
    {
        $userPermissions = $this->accessibleMenuItems->pluck('id')->toArray();

        $groupPermissions = $this->usersGroup ? $this->usersGroup->menuItems->pluck('id')->toArray() : [];

        return array_unique(array_merge($userPermissions, $groupPermissions));
    }
}
