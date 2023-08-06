<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="home-tab">
                            <div class="d-sm-flex align-items-center justify-content-between border-bottom mb-3">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active ps-0" id="courses-tab" data-bs-toggle="tab" href="#courses" role="tab" aria-controls="courses" aria-selected="true">Courses</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">Assignments</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="courses" role="tabpanel" aria-labelledby="courses-tab">
                                    <!-- Display courses here -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ __('Active Courses') }} - {{$courses->count()}}</h4>
                                            <p class="card-description">{{ Auth::user()->student->department->department_name }}</p>
                                            <div class="row">
                                                @foreach($courses as $key => $course)
                                                    <div class="col-md-4 grid-margin">
                                                        <a href="{{ route('course.show', ['id' => $course->id]) }}">
                                                            <div class="card bg-twitter d-flex align-items-start card-hover">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-row align-items-start">
                                                                        <i class="ti-bookmark-alt text-white icon-md"></i>
                                                                        <div class="ms-3">
                                                                            <h5 class="text-white text-left fw-bold">{{ $course->course_name }}</h5>
                                                                            <p class="mt-1 text-white text-left card-text">{{ $course->course_code }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                                    <!-- Display assignments here -->
                                    <div class="tab-pane fade show active" id="assignments">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Assignments - {{$assignments->count()}}</h5>
                                                <div class="table-responsive">
                                                    <table id="assignment-table" class="table table-hover dataTable">
                                                        <thead>
                                                            <tr>
                                                                <th class="sorting sorting_asc" tabindex="0" aria-controls="assignment-table">No.</th>
                                                                <th class="sorting" tabindex="0" aria-controls="assignment-table">Topic</th>
                                                                <th class="sorting" tabindex="0" aria-controls="assignment-table">Course Name</th>
                                                                <th class="sorting" tabindex="0" aria-controls="assignment-table">Course Code</th>                                                                @if(Auth::user()->hasRole('student'))
                                                                <th class="sorting" tabindex="0" aria-controls="assignment-table">Status</th>                                                                @endif
                                                                <th class="sorting" tabindex="0" aria-controls="assignment-table">Due Date</th>
                                                                <th>View</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($assignments as $key => $assignment)
                                                                    <tr>
                                                                        <td class="sorting-1">{{ $key + 1 }}</td>
                                                                        <td> {{ Illuminate\Support\Str::limit($assignment->topic, 30) }}</td>
                                                                        <td> {{ $assignment->course->course_name }}</td>
                                                                        <td> {{ $assignment->course->course_code }}</td>                                                                        @if(Auth::user()->hasRole('student'))
                                                                        <td>
                                                                            @if( $assignment->submissionStatus($assignment->id) === "pending")
                                                                                @if($assignment->daysRemaining() > -1)
                                                                                    <p class="badge badge-pill badge-warning">Due - {{$assignment->daysRemaining()}} days</p>
                                                                                @else
                                                                                    <p class="badge badge-pill badge-dark">Missed</p>
                                                                                @endif
                                                                            @else
                                                                                @if($assignment->submissionStatus($assignment->id) === "approved")
                                                                                    <p class="badge badge-pill badge-success">Approved</p>
                                                                                @else
                                                                                    <p class="badge badge-pill badge-danger">Declined</p>
                                                                                @endif
                                                                            @endif
                                                                        </td>                                                                        @endif
                                                                        <td>{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</td>
                                                                        <td><a href="{{ route('assignment.show', ['id' => $assignment->id]) }}"><button class="btn btn-icon btn-primary"><span class="mdi mdi-eye"></span></button></a></td>
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
                </div>
            </div>
        </div>
    </div>
    <script>
        let assignmentTable = new DataTable("#assignment-table", {
            lengthMenu:[10,20,50,100],
            pageLength:10,
        })
    </script>
</x-app-layout>
