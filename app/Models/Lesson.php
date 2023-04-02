<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // This is basic information of a lesson
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
        'is_public',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get the user that owns the lesson.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get lesson details
     */
    public function lessonDetail(): HasMany
    {
        return $this->hasMany(LessonDetail::class);
    }

    /**
     * Get the course that owns the lesson.
     */
    public function course(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

    //save log when logged user access lesson and not if guest
    public function logAccess()
    {
        if (auth()->check()) {
            $this->logs()->create([
                'user_id' => auth()->id(),
                'lesson_id' => $this->id,
                'accessed_at' => now(),
            ]);
        } else {
            return;
        }
    }
}
