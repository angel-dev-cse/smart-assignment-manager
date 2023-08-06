<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        // Fetch the submission details by ID
        $submission = Submission::findOrFail($id);
        $assignment = $submission->assignment;

        return view('submission', compact('assignment', 'submission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function edit(Submission $submission)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'description' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png',
        ]);

        $submission = Submission::findOrFail($request->submission_id);
        // Upload the submission file and get the file path
        $filePath = $request->file('file')->store('public/submissions');

        $fileUrl = str_replace('public/', '', $filePath);

        // Update the submission with the new values
        $submission->update([
            'description' => $request->input('description'),
            'file_path' => $fileUrl,
        ]);

        // create notification
        $teacher = $submission->assignment->teacher; // Assuming you have the relationship set up to retrieve students of the course
       
        $data = [
            'user_id' => $teacher->user->id,
            'title' => 'Submission Updated!',
            'description' => 'in ' . $submission->assignment->course->course_name,
            'route' => 'submission.show',
            'route_id' => $submission->id,
        ];

        $notificationController = new NotificationController();
        $notificationController->store(new Request($data));

        // return Redirect::route('profile.edit')->with('status', 'profile-updated');

        return redirect()->route('submission.show', ['id' => $request->submission_id])->with('success', 'Submission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submission $submission)
    {
        //
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'description' => 'required',
            'file' => 'required|mimes:pdf,doc,docx,xlsx,csv,jpg,jpeg,png',
        ]);

        // Get the authenticated student's ID
        $studentId = Auth::user()->student->id;

        // Upload the submission file and get the file path
        $filePath = $request->file('file')->store('public/submissions');

        $fileUrl = str_replace('public/', '', $filePath);

        // Create the submission
        $submission = Submission::create([
            'assignment_id' => $request->input('assignment_id'),
            'student_id' => $studentId,
            'description' => $request->input('description'),
            'file_path' => $fileUrl,
        ]);

        // create notification
        $teacher = $submission->assignment->teacher; // Assuming you have the relationship set up to retrieve students of the course
       
        $data = [
            'user_id' => $teacher->user->id,
            'title' => 'New Submission Received!',
            'description' => 'in ' . $submission->assignment->course->course_name,
            'route' => 'submission.show',
            'route_id' => $submission->id,
        ];

        $notificationController = new NotificationController();
        $notificationController->store(new Request($data));

        // Redirect back with success message
        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'review' => 'required',
            'score' => 'required|integer',
            'status' => 'required|in:approved,declined',
        ]);

        $submission = Submission::findOrFail($request->submission_id);

        // Update the submission with the new values
        $submission->update([
            'review' => $request->input('review'),
            'score' => $request->input('score'),
            'status' => $request->input('status'),
        ]);
        
        // create notification
        $student = $submission->student; // Assuming you have the relationship set up to retrieve students of the course
       
        $data = [
            'user_id' => $student->user->id,
            'title' => 'New Review Received!',
            'description' => 'in ' . $submission->assignment->course->course_name,
            'route' => 'submission.show',
            'route_id' => $submission->id,
        ];

        $notificationController = new NotificationController();
        $notificationController->store(new Request($data));
        

        // return Redirect::route('profile.edit')->with('status', 'profile-updated');

        return redirect()->route('assignment.show', ['id' => $submission->assignment_id])->with('success', 'Submission reviewed successfully!');
    }
}