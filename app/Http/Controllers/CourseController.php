<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\PreApprovedEmails;
use App\Models\Student;
use App\Models\Submission;
use App\Models\TeacherGrade;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $departments = Department::all();

        return view('admin/courses', compact('courses', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'course_name' => 'required',
            'course_code' => 'required',
            'semester' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
        ]);

        $course = new Course();
        $course->course_name = $request->input('course_name');
        $course->course_code = $request->input('course_code');
        $course->semester = $request->input('semester');
        $course->department_id = $request->input('department_id');
        $course->save();

        return redirect()->route('course.index')->with('success', 'Course created successfully!');
    }

    public function show($id)
    {
        // Fetch the course details
        $course = Course::findOrFail($id);

        $department = $course->department;
        $teacher = $course->teacher();
        $assignments = Assignment::where('course_id', $id)
            ->withCount('submissions')
            ->orderByDesc('created_at')
            ->get();

        // get score of the students
        $students = $course->students;

        // Calculate the score for each student based on approved submissions
        $studentScores = [];
        foreach ($students as $student) {
            $score = Submission::whereHas('assignment', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
                ->where('student_id', $student->id)
                ->where('status', 'approved')
                ->sum('score');

            $studentScores[$student->id] = $score;
        }

        $preApprovedEmails = PreApprovedEmails::where('course_id', $id)->get();

        $teacherGrade = TeacherGrade::where('course_id', $course->id)
            ->where('student_id', $student->id)
            ->where('teacher_id', $teacher->id)
            ->first();

        // Fetch assignments and students data conditionally based on the user's role
        // if (Auth::user()->hasRole('teacher')) {
        //     $assignments = $course->assignments; // Assuming you have the relationship set up in the models
        // } else {
        //     $students = $course->students; // Assuming you have the relationship set up in the models
        // }

        // Pass the data to the course.blade.php view
        return view('course', compact('course', 'assignments', 'teacher', 'department', 'studentScores', 'preApprovedEmails', 'teacherGrade'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'semester' => 'required|integer|min:1|max:8',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Find the course by its ID
        $courseId = $request->input('course_id');
        $course = Course::findOrFail($courseId);

        // Update the course with the new data
        $course->update([
            'course_name' => $request->input('course_name'),
            'course_code' => $request->input('course_code'),
            'semester' => $request->input('semester'),
            'department_id' => $request->input('department_id'),
        ]);

        // Redirect back to the course listing page with a success message
        return redirect()->route('course.index')->with('success', 'Course updated successfully!');
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $courseId = $request->input('course_id');

        // Find the application by ID and ensure it belongs to the authenticated teacher
        $course = Course::findOrFail($courseId);

        $course->deleteCourse();

        return redirect()->route('course.index')->with('success', 'Course deleted successfully!');
    }

    public function getScore(Course $course)
    {
        // Get the students in the course


        return view('course', compact('course', 'studentScores'));
    }

    public function addPreApprovedEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'student_email' => 'required|string|email|unique:pre_approved_emails,student_email'
            ]);

            $courseId = $validated['course_id'];
            $student = Student::whereHas('user', function ($query) use ($validated) {
                $query->where('email', $validated['student_email']);
            })->first();

            // Allow previous enrollments requests that are still pending
            if ($student) {
                $studentId = $student->id;

                // dd($studentId);
                $enrollment = Enrollment::where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->where('status', 'pending');

                if ($enrollment) {
                    $enrollmentData = $enrollment->first();
                    $studentUserId = $enrollmentData->student->user->id;
                    $status = "approved";
                    $courseName = $enrollmentData->course->course_name;

                    $enrollment->update(['status' => 'approved']);

                    $data = [
                        'user_id' => $studentUserId,
                        'title' => 'Enrollment application ' . $status . '!',
                        'description' => 'in ' . $courseName,
                        'route' => 'student.application'
                    ];

                    $notificationController = new NotificationController();
                    $notificationController->store(new Request($data));
                }
            }

            PreApprovedEmails::create($validated);

            return redirect()->back()->with('success', 'Email added successfully!')->withFragment('preapproved-emails');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->with('error', 'Email already exists in the pre-approved list.')->withFragment('preapproved-emails');
            }
            return redirect()->back()->with('error', 'An error occurred while adding the email.')->withFragment('preapproved-emails');
        }
    }

    public function updatePreApprovedEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:pre_approved_emails,id',
                'student_email' => 'required|string|email|unique:pre_approved_emails,student_email,' . $request->id
            ]);

            $preApprovedEmail = PreApprovedEmails::findOrFail($validated['id']);
            $preApprovedEmail->update(['student_email' => $validated['student_email']]);

            return redirect()->back()->with('success', 'Email updated successfully!')->withFragment('preapproved-emails');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return redirect()->back()->with('error', 'Email already exists in the pre-approved list.')->withFragment('preapproved-emails');
            }
            return redirect()->back()->with('error', 'An error occurred while updating the email.')->withFragment('preapproved-emails');
        }
    }


    public function deletePreApprovedEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:pre_approved_emails,id',
            ]);

            PreApprovedEmails::destroy($validated['id']);

            return redirect()->back()->with('success', 'Email removed successfully!')->withFragment('preapproved-emails');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'An error occurred while removing the email.')->withFragment('preapproved-emails');
        }
    }
}