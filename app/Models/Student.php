<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'roll',
        'department_id',
        'semester',
        'session',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Approved enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)->where('status', 'approved');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // approved courses
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
            ->wherePivot('status', 'approved');
    }

    // teachers in approved courses
    public function teachers()
    {
        return Teacher::whereIn('id', function ($query) {
            $query->select('teacher_id')
                ->from('teaches')
                ->whereIn('course_id', function ($query) {
                    $query->select('course_id')
                        ->from('enrollments')
                        ->where('student_id', $this->id)
                        ->where('status', 'approved');
                });
        })->get();
    }
}