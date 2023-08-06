<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:approved,declined'
        ]);

        $user = User::findOrFail($request->id);
        $status = $request->input('status');

        // Update the submission with the new values
        $user->update([
            'status' => $status
        ]);

        if ($status === "approved") {
            return redirect()->back()->with('success', $user->name . ' ' . $status . ' as ' . $user->role . ' successfully!');
        } else {
            return redirect()->back()->with('error', $user->name . ' ' . $status . ' as ' . $user->role . ' successfully!');
        }
    }

    public function registrations() {
        $studentRegistrations = User::with('student')
                ->where('role', 'student')
                ->where('status', 'pending')
                ->get();

            $teacherRegistrations = User::with('teacher')
                ->where('role', 'teacher')
                ->where('status', 'pending')
                ->get();

            return view('admin.registrations', compact('studentRegistrations', 'teacherRegistrations'));
    }

    public function courses() {
        
    }

    public function departments() {
        $departments = Course::all();

        return view('admin/departments', compact('departments'));
    }
}