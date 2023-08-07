<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Department;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $teacherName = $course->teacher()->user->name;
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

        // Fetch assignments and students data conditionally based on the user's role
        // if (Auth::user()->hasRole('teacher')) {
        //     $assignments = $course->assignments; // Assuming you have the relationship set up in the models
        // } else {
        //     $students = $course->students; // Assuming you have the relationship set up in the models
        // }

        // Pass the data to the course.blade.php view
        return view('course', compact('course', 'assignments', 'teacherName', 'department', 'studentScores'));
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
}