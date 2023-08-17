<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreApprovedEmails extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_email'
    ];

    public function course() : BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function teacher() : BelongsTo {
        return $this->course()->teacher();
    }


}
