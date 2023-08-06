<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Teach;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    //
    public function index() {
        $enrollments = Enrollment::with('student', 'course')
                ->where('status', 'pending')
                ->get();

        $teaches = Teach::with('teacher', 'course')
                ->where('status', 'pending')
                ->get();

            // dd($studentRegistrations);

            return view('admin.applications', compact('enrollments', 'teaches'));
    }
}
