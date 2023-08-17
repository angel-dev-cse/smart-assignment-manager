<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card card-shadow mx-36">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="">
                                <div class="card-body text-center">
                                    @if ($user->role === 'student')
                                        <img  class="rounded-circle mx-auto mb-4" width="150" src="{{ asset('startheme/images/student.png') }}" alt="Profile Image">
                                    @elseif ($user->role === 'teacher')
                                        <img  class="rounded-circle mx-auto mb-4" width="150" src="{{ asset('startheme/images/teacher.png') }}" alt="Profile Image">
                                    @else
                                        <img  class="rounded-circle mx-auto mb-4" width="150" src="{{ asset('startheme/images/admin.png') }}" alt="Profile Image">
                                    @endif
                                    
                                    <h4>{{ $user->name }}</h4>
                                    <p class="text-secondary">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="">
                                <div class="card-body">
                                    @if ($user->role === 'student')
                                        <h4 class="card-title">Student Information</h4>
                                        <p><b>Roll:</b> {{ $user->student->roll }}</p>
                                        <p><b>Semester:</b> {{ $user->student->semester }}</p>
                                        <p><b>Session:</b> {{ $user->student->session }}</p>
                                        <p><b>Department:</b> {{ $user->student->department->department_name }}</p>
                                        <p><b>Total Marks:</b> {{ $score }}</p>
                                    @elseif ($user->role === 'teacher')
                                        <h4 class="card-title">Teacher Information</h4>
                                        <p><b>Qualification:</b> {{ $user->teacher->qualification }}</p>
                                        <p><b>Department:</b> {{ $user->teacher->department->department_name }}</p>
                                        <p><b>Teacher's Grade:</b> {{ $user->teacher->getGrading() }}</p>
                                    @endif
                                    @if (auth()->user() && $user->id === auth()->user()->id)
                                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn mt-20">Update Profile</a>
                                    @endif

                                    @if ($user->id !== auth()->user()->id)
                                        <a href="#" class="btn btn-info open-chat-popup mt-4" data-recipient="{{$user->id}}">Message</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>
