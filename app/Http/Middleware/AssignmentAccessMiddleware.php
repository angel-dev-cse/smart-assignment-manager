<?php

namespace App\Http\Middleware;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Teach;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $assignmentId = $request->route('id');

        if (is_null($assignmentId)) {
            $assignmentId = $request->input('assignment_id');
        }

        $user = Auth::user();

        $assignment = Assignment::findorFail($assignmentId);

        if (is_null($assignment)) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('teacher')) {
            $isApproved = $assignment->where('teacher_id', $user->teacher->id)->exists();
        } elseif ($user->hasRole('student')) {
            $isApproved = Enrollment::where('student_id', $user->student->id)
                ->where('status', 'approved')
                ->whereHas('course', function ($query) use ($assignmentId) {
                    $query->join('assignments', 'courses.id', '=', 'assignments.course_id')
                        ->where('assignments.id', $assignmentId);
                })->exists();
        } else {
            // Redirect to dashboard if the user is neither teacher nor student
            return redirect()->route('dashboard');
        }

        if (!$isApproved) {
            // Redirect to dashboard if the user doesn't have access to the course
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}