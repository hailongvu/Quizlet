<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    // Fill able fields
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'is_public',
        'password',
    ];

    // Hidden fields
    protected $hidden = [
        'password',
    ];

    // Cast fields
    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Get the user that owns the lesson.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get lesson belong to source
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'courses_lesson', 'courses_id', 'lesson_id');
    }

    // Get class.js belong to folder
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_folder', 'folder_id', 'class_id');
    }
}
