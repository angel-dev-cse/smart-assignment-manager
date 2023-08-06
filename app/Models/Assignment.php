<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'course_id',
        'topic',
        'description',
        'marks',
        'file_path',
        'deadline'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function hasSubmitted($studentId): bool
    {
        return $this->submissions()->where('student_id', $studentId)->exists();
    }

    public function deleteAssignment()
    {
        $this->delete();
    }

    public function daysRemaining(): ?int
    {
        // Get the deadline date from the "deadline" attribute 2023-08-18 00:00:00
        $deadline = $this->deadline;

        // Check if the deadline is not null and is a valid date
        if ($deadline !== null) {
            // Calculate the days remaining using Carbon
            $today = Carbon::today();
            $daysRemaining = $today->diffInDays($deadline, false);

            // Return the positive number of days remaining (or negative if the deadline has passed)
            return $daysRemaining;
        }

        return null; // Return null in case of invalid deadline or missing data
    }

    public function submissionStatus($id)
    {
        $user = Auth::user();
        $submission = Submission::all()
            ->where('assignment_id', $id)
            ->where('student_id', $user->student->id)->first();

        if (is_null($submission)) {
            return "pending";
        } else {
            return $submission->status;
        }
    }
}