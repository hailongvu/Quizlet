<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticate implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'username',
        'email',
        'date_of_birth',
        'status',
        'password',
        'email_verified_at'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * 1 user can have 1 roles
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * 1 user can have many lessons
     */
    public function lesson(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    // get all permissions of user
    public function getAllPermissions()
    {
        return $this->role()->with('permissions')->first()->permissions;
    }

    // check if user has permission
    public function hasPermission($permission)
    {
        return $this->getAllPermissions()->contains('name', $permission);
    }

    // check user is super admin
    public function isSuperAdmin(): bool
    {
        return $this->role->name == 'super';
    }

    // Course belong to user
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // get school of user
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
