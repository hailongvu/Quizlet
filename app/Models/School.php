<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'phone',
        'email',
        'website',
        'logo',
    ];

    // get all manager in a school
    public function managers(): HasMany
    {
        return $this->hasMany(User::class, 'school_id', 'id');
    }

    // get all student in a school
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'school_id', 'id');
    }

    // get all teachers in a school
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class, 'school_id', 'id');
    }

    // get all class.js in a school
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class, 'school_id', 'id');
    }

}
