<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use App\Models\Teach;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $courseId = $request->route('id');
        $user = Auth::user();

        if ($user->hasRole('teacher')) {
            $isApproved = Teach::where('teacher_id', $user->teacher->id)
                              ->where('course_id', $courseId)
                              ->where('status', 'approved')
                              ->exists();
        } elseif ($user->hasRole('student')) {
            $isApproved = Enrollment::where('student_id', $user->student->id)
                                   ->where('course_id', $courseId)
                                   ->where('status', 'approved')
                                   ->exists();
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
