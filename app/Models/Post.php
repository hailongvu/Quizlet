<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    // make model like stackoverflow question
    protected $fillable = [
        'user_id',
        'class_id',
        'slug',
        'name',
        'description',
        'status',
        'score_reporting',
        'tags',
        'views',
    ];

    // get user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // get class
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }

    // get comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
