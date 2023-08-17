<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;

class TeacherGradeController extends Controller
{
    //
    public function store(Request $request) {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|integer'
        ]);

        $teacher = Teacher::findorFail($validated['teacher_id']);

        TeacherGrade::create($validated);

        return redirect()->back()->with('success', $teacher->user->name . ' was graded successfully!');
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'id' => 'required|exists:teacher_grades,id',
            'grade' => 'required|integer',
        ]);

        $teacherGrade = TeacherGrade::findorFail($validated['id']);

        $teacherGrade->update(['grade'=>$validated['grade']]);

        return redirect()->back()->with('success', $teacherGrade->teacher->user->name . '\'s grade updated successfully!');
    }
}
