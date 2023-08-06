<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = [
        'assignment_id',
        'student_id',
        'description',
        'file_path',
        'status',
        'review',
        'score'
    ];

    public function assignment() : BelongsTo {
        return $this->belongsTo(Assignment::class);
    }

    public function student() : BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
