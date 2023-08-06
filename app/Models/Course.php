<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'course_name',
        'course_code',
        'semester',
    ];

    public function teacher()
    {
        return $this->belongsToMany(Teacher::class, 'teaches', 'course_id', 'teacher_id')
            ->wherePivot('status', 'approved')->first();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // only approved enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)->where('status', 'approved');
    }

    // only approved students
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            Enrollment::class,
            'course_id', // Foreign key on the enrollments table
            'id', // Foreign key on the students table
            'id', // Local key on the courses table
            'student_id' // Local key on the enrollments table
        )->where('enrollments.status', 'approved');
    }

    public function teaches()
    {
        return $this->hasMany(Teach::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function deleteCourse() {
        $this -> delete();
    }
}