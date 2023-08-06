<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $name = $user->name;

        if ($request->user()->hasRole('teacher')) {
            $teacherId = Auth::user()->teacher->id;
            $courses = Teacher::findOrFail($teacherId)->courses;

            $assignments = Assignment::whereHas('course', function ($query) use ($teacherId) {
                $query->whereHas('teaches', function ($query) use ($teacherId) {
                    $query->where('teacher_id', $teacherId)
                          ->where('status', 'approved');
                });
            })->with('course')->get();

            return view('teacher.dashboard', compact('name', 'courses', 'assignments'));
        } elseif ($request->user()->hasRole('student')) {
            $studentId = Auth::user()->student->id;
            $courses = Student::findOrFail($studentId)->courses;

            $assignments = Assignment::whereHas('course', function ($query) use ($studentId) {
                $query->whereHas('enrollments', function ($query) use ($studentId) {
                    $query->where('student_id', $studentId)
                          ->where('status', 'approved');
                });
            })->with('course')->get();

            return view('student.dashboard', compact('name', 'courses', 'assignments'));
        } elseif ($request->user()->hasRole('admin')) {
            // pending registrations
            $studentCount = User::all()
                ->where('role', 'student')
                ->where('status', 'approved')
                ->count();

            $teacherCount = User::all()
                ->where('role', 'teacher')
                ->where('status', 'approved')
                ->count();

            $courseCount = Course::all()
                ->count();

            $departmentCount = Department::all()
                ->count();

            return view('admin.dashboard', compact('studentCount', 'teacherCount', 'courseCount', 'departmentCount'));

        }

        // Handle other roles or default behavior here
        return view('dashboard');
    }
}