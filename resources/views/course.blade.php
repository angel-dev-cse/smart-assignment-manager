<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <!-- Left side: Course Details and Create Assignment Form -->
                    <div class="col-md-3">
                        <div class="card card-shadow bg-primary">
                            <div class="card-header">{{ $course -> course_name }}</div>
                            <div class="card-body">
                                <p class="card-description">Course Code: {{ $course -> course_code }}</p>
                                <p class="card-text text-mute">Course Teacher</p>
                                <h5 class="card-title">{{ $teacherName }}</h5>
                                <p class="card-text text-mute">Total Students</p>
                                <h5 class="card-title">{{ $course->students->count() }}</h5>
                                <p class="card-text text-mute">{{ $department -> description }}</p>
                                <h3 class="card-text text-mute">{{ $department -> department_name }}</h3>
                                <!-- Add more course details here -->
                            </div>
                        </div>
                        @if(Auth::user()->hasRole('teacher'))
                            <div class="card card-shadow mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Create Assignment</h5>
                                    <form class="forms-sample" method="POST" action="{{ route('assignment.create')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group m-1">
                                            <label for="topic" class="form-label">Topic</label>
                                            <input type="text" class="form-control" id="topic" name="topic" required>
                                        </div>
                                        <div class="form-group m-1">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" style="height:8rem" id="description" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group m-1">
                                            <label for="marks" class="form-label">Marks</label>
                                            <input type="number" class="form-control" id="marks" name="marks" required>
                                        </div>
                                        <div class="form-group m-1">
                                            <label for="file" class="form-label">File</label>
                                            <input type="file" class="form-control" id="file" name="file" required>
                                        </div>
                                        <div class="form-group m-1">
                                            <label for="deadline" class="form-label">Deadline</label>
                                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                                        </div>
                                        <!-- Add more fields for file, marks, and deadline time -->
                                        <input type="hidden" id="course_id" name="course_id" value="{{ $course->id }}" />

                                        <button type="submit" class="btn btn-primary active me-2">Create Assignment</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Right side: Assignments and Students Tabs -->
                    <div class="col-md-9">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#assignments">Assignments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#students">Students</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Assignments Tab Content -->
                            <div class="tab-pane fade show active" id="assignments">
                                <div class="card card-shadow table-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignments</h5>
                                        <div class="table-responsive">
                                            <table id="assignment-table" class="table dataTable table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="assignment-table">#</th>
                                                        <th class="sorting" tabindex="0" aria-controls="assignment-table">Topic</th>
                                                        @if(Auth::user()->hasRole('student'))
                                                            <th class="sorting" tabindex="0" aria-controls="assignment-table">Status</th>
                                                        @endif
                                                        <th class="sorting" tabindex="0" aria-controls="assignment-table">Due Date</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($course->assignments as $key => $assignment)
                                                            <tr>
                                                                <td class="sorting_1">{{ $key + 1 }}</td>
                                                                <td>{{ Illuminate\Support\Str::limit($assignment->topic, 30) }}</td>
                                                                @if(Auth::user()->hasRole('student'))
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
                                                                    </td>
                                                                @endif
                                                                <td>{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</td>
                                                                <td><a href="{{ route('assignment.show', ['id' => $assignment->id]) }}"><button class="btn btn-circle btn-primary"><span class="mdi mdi-eye"></span></button></a></td>
                                                            </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Students Tab Content -->
                            <div class="tab-pane fade" id="students">
                                <div class="card card-shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Students</h5>
                                        <div class="table-responsive">
                                            <table id="student-table" class="table table-hover dataTable">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="student-table">No.</th>
                                                        <th class="sorting" tabindex="0" aria-controls="student-table">Name</th>
                                                        <th class="sorting" tabindex="0" aria-controls="student-table">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="student-table">Marks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($course->students as $key => $student)
                                                        <tr>
                                                            <td class="sorting_1">{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('profileForm{{$key}}').submit();"><b>{{ $student->user->name }}</b></a>

                                                                <form id="profileForm{{$key}}" class="form" method="POST" action="{{ route('profile.show') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $student->user->id }}" />
                                                                </form>
                                                            </td>
                                                            <td>{{ $student->roll}}</td>
                                                            <td>{{ $studentScores[$student->id]}}</td>
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
    <script>
        let assignmentTable = new DataTable('#assignment-table', {
            lengthMenu: [5, 10, 15, 20, 50, 100],
            pageLength: 10,
            "columnDefs": [ {
                "targets": [3],
                "searchable": false,
                "sortable": false,
            } ]
        });

        let studentTable = new DataTable('#student-table', {
            lengthMenu: [5, 10, 15, 20, 50, 100],
            pageLength: 10,
        });
    </script>
</x-app-layout>
