<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // Validate the form data
        $request->validate([
            'course_id' => 'required|integer',
            'topic' => 'required',
            'description' => 'required',
            'marks' => 'required|integer',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,csv,jpeg,png',
            // You can modify the allowed file types and maximum size as needed
            'deadline' => 'required|date',
        ]);

        // Get the course based on the $id
        $course = Course::findOrFail($request->input('course_id'));

        // Upload the file and get the file path
        $filePath = $request->file('file')->store('public/assignments');

        $fileUrl = str_replace('public/', '', $filePath);

        // Create the assignment
        $assignment = new Assignment([
            'topic' => $request->input('topic'),
            'description' => $request->input('description'),
            'marks' => $request->input('marks'),
            'file_path' => $fileUrl,
            'deadline' => $request->input('deadline'),
        ]);

        // Associate the assignment with the course and teacher
        $assignment->course()->associate($course);
        $assignment->teacher()->associate(Auth::user()->teacher);

        //dd($assignment);

        $assignment->save();

        // create notification
        $students = $course->students; // Assuming you have the relationship set up to retrieve students of the course
        foreach ($students as $student) {
            $data = [
                'user_id' => $student->user->id,
                'title' => 'New Assignment Created',
                'description' => 'in ' . $course->course_name,
                'route' => 'assignment.show',
                'route_id' => $assignment->id,
            ];

            $notificationController = new NotificationController();
            $notificationController->store(new Request($data));
        }

        // Redirect back with success message
        return redirect()->back()->with('success', 'Assignment created successfully!');
    }

    public function show($id)
    {
        $user = Auth::user();
        $assignment = Assignment::findOrFail($id);

        $totalStudent = $assignment->students()->count();
        $submittedStudent = $assignment->submissions->count();

        $progressValue = $totalStudent > 0 ? round(($submittedStudent / $totalStudent) * 100) : 0;
        $progressText = $submittedStudent . "/" . $totalStudent;

        if ($user->hasRole('teacher')) {
            $submissions = $assignment->submissions;

            return view('assignment', compact('assignment', 'submissions', 'progressText', 'progressValue'));
        } else {
            $submission = $user->student->submissions->where('assignment_id', $id)->first();

            return view('assignment', compact('assignment', 'submission', 'progressText', 'progressValue'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|integer',
            'topic' => 'required',
            'description' => 'required',
            'marks' => 'required|integer',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,csv,jpeg,png',
            'deadline' => 'required|date',
        ]);

        // Get the course based on the $id
        $assignment = Assignment::findOrFail($request->input('assignment_id'));

        // Upload the file and get the file path
        $filePath = $request->file('file')->store('public/assignments');

        $fileUrl = str_replace('public/', '', $filePath);

        $assignment->update([
            'topic' => $request->input('topic'),
            'description' => $request->input('description'),
            'marks' => $request->input('marks'),
            'file_path' => $fileUrl,
            'deadline' => $request->input('deadline')
        ]);

        // create notification
        $course = $assignment->course;
        $students = $course->students; // Assuming you have the relationship set up to retrieve students of the course

        foreach ($students as $student) {
            $data = [
                'user_id' => $student->user->id,
                'title' => 'Assignment Updated!',
                'description' => 'in ' . $course->course_name,
                'route' => 'assignment.show',
                'route_id' => $assignment->id,
            ];

            $notificationController = new NotificationController();
            $notificationController->store(new Request($data));
        }

        // return Redirect::route('profile.edit')->with('status', 'profile-updated');

        return redirect()->route('assignment.show', ['id' => $request->assignment_id])->with('success', 'Submission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assignment  $assignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assignment $assignment)
    {

    }

    public function delete(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
        ]);

        $assignmentId = $request->input('assignment_id');

        // Find the application by ID and ensure it belongs to the authenticated teacher
        $assignment = Assignment::findOrFail($assignmentId);
        $courseId = $assignment->course->id;

        if ($assignment->teacher_id === Auth::user()->teacher->id) {
            $assignment->deleteAssignment();
            return redirect()->route('course.show', ['id' => $courseId])->with('success', 'Assignment deleted successfully!');
        } else {
            return redirect()->route('course.show', ['id' => $courseId])->with('error', 'You are not authorized to delete this assignment!');
        }
    }
}