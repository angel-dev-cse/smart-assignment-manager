<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'qualification',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'teaches', 'teacher_id', 'course_id')
            ->wherePivot('status', 'approved');
    }

    // Approved students
    public function students()
    {
        return Student::whereIn('id', function ($query) {
            $query->select('student_id')
                  ->from('enrollments')
                  ->whereIn('course_id', function ($query) {
                      $query->select('course_id')
                            ->from('teaches')
                            ->where('teacher_id', $this->id)
                            ->where('status', 'approved');
                  });
        })->get();
    }

    public function teaches() : HasMany {
        return $this->hasMany(Teach::class);
    }

}