<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::all();

        return view('auth.register', compact('departments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher'], // Make sure 'student' and 'teacher' are the only valid roles
            'roll' => ['required_if:role,student', 'integer'],
            // Add validation for class (required if role is student)
            'semester' => ['required_if:role,student', 'integer'],
            'session' => ['required_if:role,student'],
            // Add validation for roll (required if role is student)
            'qualification' => ['required_if:role,teacher'], // Add validation for qualification (required if role is teacher)
            // Add more validations for other fields specific to teacher and student
        ]);

        // Create the user with common data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'=> $request->role,
        ]);

        // Save the role-specific data
        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'department_id' => $request->department,
                'roll' => $request->roll,
                'semester' => $request->semester,
                'session' => $request->session,
            ]);
        } elseif ($request->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'department_id' => $request->department,
                'qualification' => $request->qualification,
            ]);
        }

        // Fire the registered event
        event(new Registered($user));

        // Log in the user
        Auth::login($user);

        // Redirect the user to their respective dashboard based on their role
        // if ($user->role === 'student') {
        //     return redirect()->route('student.dashboard');
        // } elseif ($user->role === 'teacher') {
        //     return redirect()->route('teacher.dashboard');
        // } else {
        //     // Add handling for other roles if needed
        //     return redirect(RouteServiceProvider::HOME);
        // }

        return redirect(RouteServiceProvider::HOME);
    }

    public function reapply(Request $request) {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:declined'
        ]);

        // Create the user with common data
        $user = User::findorFail($request->id);

        // Save the role-specific data
        $user->status = "pending";
        $user->save();

        return redirect()->back()->with('success', 'Application resubmitted for verification!');
    }
}