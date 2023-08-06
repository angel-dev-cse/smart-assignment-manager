<?php

namespace App\Http\Middleware;

use App\Models\Enrollment;
use App\Models\Submission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionAccessMiddleware
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
        $submissionId = $request->route('id');

        if (is_null($submissionId)) {
            $submissionId = $request->input('submission_id');
        }

        $user = Auth::user();

        $submission = Submission::findorFail($submissionId);

        if (is_null($submission)) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('teacher')) {
            $isApproved = $submission->assignment->where('teacher_id', $user->teacher->id)->exists();
        } elseif ($user->hasRole('student')) {
            $isApproved = $submission->where('student_id', $user->student->id)->exists();
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
