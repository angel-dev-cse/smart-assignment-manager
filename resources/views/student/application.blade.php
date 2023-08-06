<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{ __('Course Application') }}</h4>
                                <p class="card-description">Apply for a course in <b>Semester - {{ $semester }}</b>, <b>{{ $department->department_name }}</b></p>
                                <form class="forms-sample" method="POST" action="{{ route('student.application') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="course_id">{{ __('Select Course') }}</label>
                                        <select name="course_id" id="course_id"
                                            class="form-control @error('course_id') is-invalid @enderror">
                                            <option value="">-- Select Course --</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_code }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary active">
                                        {{ __('Apply') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{ __('Pending Applications') }}</h4>
                                <div class="table-responsive nowrap-table">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Department Name</th>
                                                <th>Semester</th>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingApplications as $key => $application)
                                                <tr class="text-center">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $department->department_name }}</td>
                                                    <td>{{ $application->course->semester }}</td>
                                                    <td>{{ $application->course->course_code }}</td>
                                                    <td>{{ $application->course->course_name }}</td>
                                                    <td><label class="badge badge-warning">{{ $application->status }}</label></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('student.application.delete') }}" onsubmit="return confirm('Are you sure you want to cancel this application?')">
                                                            @csrf
                                                            <input type="hidden" name="enrollment_id" value="{{ $application->id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm btn-floating active"><i class="mdi mdi-delete btn-icon"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
