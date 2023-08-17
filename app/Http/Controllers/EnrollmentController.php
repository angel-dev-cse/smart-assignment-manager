<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\PreApprovedEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $studentId = $user->student->id;
        $semester = $user->student->semester;
        $departmentId = $user->student->department_id;

        $department = Department::findOrFail($departmentId);


        $courses = Course::where('semester', $semester)
            ->where('department_id', $departmentId)
            ->whereNotIn('id', function ($query) use ($studentId) {
                $query->select('course_id')
                    ->from('enrollments')
                    ->where('student_id', $studentId)
                    ->whereIn('status', ['approved', 'pending']);
            })
            ->with('department')
            ->get();



        $pendingApplications = Enrollment::where('student_id', $studentId)
            ->where('status', 'pending')
            ->with('course')
            ->get();

        // dd($pendingApplications);

        return view('student.application', compact('courses', 'semester', 'department', 'pendingApplications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $studentId = Auth::user()->student->id;
        $studentEmail = Auth::user()->email;
        $courseId = $request->input('course_id');

        // Check if the student's email is in the pre_approved_emails table for the given course_id
        $isPreApproved = PreApprovedEmails::where('course_id', $courseId)
            ->where('student_email', $studentEmail)
            ->exists();

        $status = $isPreApproved ? 'approved' : 'pending';

        // Save the course application to the enrollments table
        $enrollment = Enrollment::create([
            'course_id' => $courseId,
            'student_id' => $studentId,
            'status' => $status,
        ]);

        if ($status === 'approved') {
            // create notification for preapproved emails
            $data = [
                'user_id' => $enrollment->student->user->id,
                'title' => 'Enrollment application ' . $status . '!',
                'description' => 'in ' . $enrollment->course->course_name,
                'route' => 'student.application'
            ];

            $notificationController = new NotificationController();
            $notificationController->store(new Request($data));

            // redirect to homepage with success message
            return redirect()->route('dashboard')->with('success', 'Course application approved!');
        }

        return redirect()->route('student.application')->with('success', 'Course application submitted successfully!');
    }


    public function delete(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
        ]);

        $enrollmentId = $request->input('enrollment_id');

        // Find the application by ID and ensure it belongs to the authenticated teacher
        $enrollment = Enrollment::findOrFail($enrollmentId);
        if ($enrollment->student->id === Auth::user()->student->id) {
            $enrollment->deleteEnrollment();
            return redirect()->route('student.application')->with('success', 'Application canceled successfully!');
        } else {
            return redirect()->route('student.application')->with('error', 'You are not authorized to cancel this application!');
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:enrollments,id',
            'status' => 'required|in:approved,declined'
        ]);

        $enrollment = Enrollment::findOrFail($request->id);
        $status = $request->input('status');

        if ($status === 'approved') {
            // save with `approved` value, delete if `declined`
            $enrollment->status = $status;
            $enrollment->save();
        } else {
            $enrollment->deleteEnrollment();
        }

        // create notification
        $data = [
            'user_id' => $enrollment->student->user->id,
            'title' => 'Enrollment application ' . $status . '!',
            'description' => 'in ' . $enrollment->course->course_name,
            'route' => 'student.application'
        ];

        $notificationController = new NotificationController();
        $notificationController->store(new Request($data));

        if ($status === "approved") {
            return redirect()->back()->with('success', $enrollment->student->user->name . '\'s request of joining ' . $enrollment->course->course_name . ' : ' . $enrollment->course->course_code . ' is accepted!');
        } else {
            return redirect()->back()->with('error', $enrollment->student->user->name . '\'s request of joining ' . $enrollment->course->course_name . ' : ' . $enrollment->course->course_code . ' is rejected!');
        }
    }
}