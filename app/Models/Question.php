<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 
        'type', 
        'question',
        'is_multiple_choice',
        'answer_option',
        'correct_answer',
        'points',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
