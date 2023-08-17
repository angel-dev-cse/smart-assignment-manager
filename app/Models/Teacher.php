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

    public function teaches(): HasMany
    {
        return $this->hasMany(Teach::class);
    }


    public function getGrading()
    {
        $grades = TeacherGrade::where('teacher_id', $this->id)->count();
        
        if ($grades > 0) {
            $grade = TeacherGrade::where('teacher_id', $this->id)->sum('grade')/$grades;
        } else {
            $grade = 0;
            return 'No rating yet!';
        }

        if ($grade >= 91 && $grade <= 100) {
            return 'A++';
        } elseif ($grade >= 81 && $grade <= 90) {
            return 'A+';
        } elseif ($grade >= 71 && $grade <= 80) {
            return 'A';
        } elseif ($grade >= 51 && $grade <= 70) {
            return 'B';
        } else {
            return 'B-';
        }
    }
}