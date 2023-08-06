<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Teach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachController extends Controller
{
    public function index()
    {
        $teacherId = Auth::user()->teacher->id;
        $departmentId = Auth::user()->teacher->department_id;
        $department = Department::findOrFail($departmentId);

        $courses = Course::whereDoesntHave('teaches', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId)->where('status', 'approved');
        })
            ->where('department_id', $departmentId)
            ->with('department')
            ->get();


        $pendingApplications = Teach::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->with('course')
            ->get();

        // dd($pendingApplications);

        return view('teacher.application', compact('courses', 'department', 'pendingApplications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $teacherId = Auth::user()->teacher->id;

        // Save the course application to the 'teaches' table
        Teach::create([
            'course_id' => $request->input('course_id'),
            'teacher_id' => $teacherId,
            'status' => 'pending',
        ]);

        return redirect()->route('teacher.application')->with('success', 'Course application submitted successfully!');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'teach_id' => 'required|exists:teaches,id',
        ]);

        $teachId = $request->input('teach_id');

        // Find the application by ID and ensure it belongs to the authenticated teacher
        $teach = Teach::findOrFail($teachId);
        if ($teach->teacher->id === Auth::user()->teacher->id) {
            $teach->deleteTeach();
            return redirect()->route('teacher.application')->with('success', 'Application canceled successfully!');
        } else {
            return redirect()->route('teacher.application')->with('error', 'You are not authorized to cancel this application!');
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:teaches,id',
            'status' => 'required|in:approved,declined'
        ]);

        $teach = Teach::findOrFail($request->id);
        $status = $request->input('status');

        if ($status === 'approved') {
            // save with `approved` value, delete if `declined`
            $teach->status = $status;
            $teach->save(); # code...
        } else {
            $teach->deleteTeach();
        }

        // create notification
        $data = [
            'user_id' => $teach->teacher->user->id,
            'title' => 'Application got ' . $status . '!',
            'description' => 'in ' . $teach->course->course_name,
            'route' => 'teacher.application'
        ];

        $notificationController = new NotificationController();
        $notificationController->store(new Request($data));

        if ($status === "approved") {
            return redirect()->back()->with('success', $teach->teacher->user->name . '\'s request of joining ' . $teach->course->course_name . ' : ' . $teach->course->course_code . ' is accepted!');
        } else {
            return redirect()->back()->with('error', $teach->teacher->user->name . '\'s request of joining ' . $teach->course->course_name . ' : ' . $teach->course->course_code . ' is rejected!');
        }
    }

}