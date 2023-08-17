<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teach extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'course_id',
        'status'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function deleteTeach()
    {
        $this->delete();
    }

}