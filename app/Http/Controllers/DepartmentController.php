<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('admin/departments', compact('departments'));
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
        $request->validate([
            'department_name' => 'required|string',
            'description' => 'required|string',
        ]);

        $department = new Department();
        $department->department_name = $request->input('department_name');
        $department->description = $request->input('description');
        
        $department->save();
        
        return redirect()->route('department.index')->with('success', 'Department created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'department_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Find the department by its ID
        $departmentId = $request->input('department_id');
        $department = Department::findOrFail($departmentId);

        // Update the department with the new data
        $department->update([
            'department_name' => $request->input('department_name'),
            'description' => $request->input('description'),
        ]);

        // Redirect back to the department listing page with a success message
        return redirect()->route('department.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $departmentId = $request->input('department_id');

        // Find the application by ID and ensure it belongs to the authenticated teacher
        $department = Department::findOrFail($departmentId);

        $department->deleteDepartment();

        return redirect()->route('department.index')->with('success', 'Department deleted successfully!');
    }
}
