<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'status',
        'score_reporting',
        'start_time',
        'end_time',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
