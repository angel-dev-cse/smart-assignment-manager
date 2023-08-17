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
                                <a href="#" class="" onclick="event.preventDefault(); document.getElementById('profileForm{{$teacher->user->id}}').submit();">
                                    <h5 class="card-title link-primary">
                                        <span class="mdi mdi-12px mdi-human-greeting"></span>
                                        <span>{{ $teacher->user->name }}</span>
                                    </h5>
                                </a>

                                <form id="profileForm{{$teacher->user->id}}" class="form" method="POST" action="{{ route('profile.show') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $teacher->user->id }}" />
                                </form>

                                <p class="card-text text-mute">Total Students</p>
                                <h5 class="card-title">{{ $course->students->count() }}</h5>
                                <p class="card-text text-mute">{{ $department -> description }}</p>
                                <h3 class="card-text text-mute">{{ $department -> department_name }}</h3>
                                <!-- Add more course details here -->
                            </div>
                        </div>
                        @if(Auth::user()->hasRole('teacher'))
                            <!-- Create Assignment Form -->
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
                    <!-- Right side : Tabs -->
                    <div class="col-md-9">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#assignments" id="assignments-nav">Assignments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#students"  id="students-nav">Students</a>
                            </li>
                            @if(auth()->user()->hasRole('teacher'))
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#preapproved-emails"  id="preapproved-emails-nav">Preapproved Students</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content">
                            <!-- Assignments Tab Content -->
                            <div class="tab-pane fade active show" id="assignments">
                                <div class="card card-shadow table-card">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignments</h5>
                                        <div class="table-responsive">
                                            <table id="assignment-table" class="table dataTable table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting sorting_desc" tabindex="0" aria-controls="assignment-table">#</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="assignment-table">Topic</th>
                                                        @if(Auth::user()->hasRole('student'))
                                                            <th class="sorting text-center" tabindex="0" aria-controls="assignment-table">Status</th>
                                                        @endif
                                                        <th class="sorting text-center" tabindex="0" aria-controls="assignment-table">Due Date</th>
                                                        @if(Auth::user()->hasRole('teacher'))
                                                            <th class="sorting text-center" tabindex="0" aria-controls="assignment-table">Submissions</th>
                                                        @endif
                                                        <th>View</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($assignments as $key => $assignment)
                                                            <tr class="text-center">
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
                                                                <td>
                                                                    @if($assignment->daysRemaining() > -1)
                                                                        <p class="fw-bold">{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</p>
                                                                    @else
                                                                        <p class="text-muted">{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</p>
                                                                    @endif
                                                                </td>
                                                                @if(Auth::user()->hasRole('teacher'))
                                                                <td>
                                                                    <div id="circleProgress3" class="progressbar-js-circle" style="width: 40px;">
                                                                        <svg class="ml-8" viewBox="0 0 100 100" style="display: block; width: 100%; height: 100%;">
                                                                            @php
                                                                                $submissionRatio = $assignment->submissions_count / $course->students->count();

                                                                                if($submissionRatio*100 <= 30) {
                                                                                    $color = "red";
                                                                                } else if ($submissionRatio*100 <=70) {
                                                                                    $color = "yellow";
                                                                                } else {
                                                                                    $color = "green";
                                                                                }

                                                                                $dashFilled = 216.769 * $submissionRatio;
                                                                            @endphp
                                                                            <path d="M 50,50 m 0,-34.5 a 34.5,34.5 0 1 1 0,69 a 34.5,34.5 0 1 1 0,-69" stroke="#eee" stroke-width="6" fill-opacity="0"></path>
                                                                            <path d="M 50,50 m 0,-34.5 a 34.5,34.5 0 1 1 0,69 a 34.5,34.5 0 1 1 0,-69" stroke="{{$color}}" stroke-width="6" fill-opacity="0" style="stroke-dasharray: {{ $dashFilled }}, 216.769; stroke-dashoffset: 0"></path>
                                                                        </svg>
                                                                        <div class="progressbar-text ml-8" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); color: rgb(0, 0, 0); font-size: 0.75rem;">
                                                                            {{$assignment->submissions_count}}
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                @endif
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
                                <div class="card table-card card-shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Students</h5>
                                        <div class="table-responsive">
                                            <table id="student-table" class="table table-hover dataTable">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="student-table">No.</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-table">Name</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-table">ID</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-table">Marks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($course->students->sortBy('roll') as $key => $student)
                                                        <tr class="text-center">
                                                            <td class="sorting_1">{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="#" class="" onclick="event.preventDefault(); document.getElementById('profileForm{{$key}}').submit();"><b>{{ $student->user->name }}</b></a>

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

                            <div class="tab-pane fade" id="preapproved-emails">
                                <!-- Pre-approved Emails -->
                                <div class="card table-card card-shadow" style="max-height:20rem">
                                    <div class="card-body">
                                        <h5 class="card-title">Allowed Student Emails</h5>
                                        <p class="card-description">Students allowed by default</p>
                                        <div class="table-responsive">
                                            <table id="preapproved-emails-table" class="table table-hover dataTable">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="preapproved-emails-table">No.</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="preapproved-emails-table">Email</th>
                                                        <th class="text-center" tabindex="0" aria-controls="preapproved-emails-table"></th>
                                                        <th class="text-center" tabindex="0" aria-controls="preapproved-emails-table"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($preApprovedEmails as $key => $preapproval)
                                                        <tr class="text-center">
                                                            <td class="sorting_1">{{ $key + 1 }}</td>
                                                            <td>{{ $preapproval->student_email }}</td>
                                                            <td class="pb-2">
                                                                <button class="btn btn-circle btn-primary edit-icon mx-2"
                                                                    data-id="{{ $preapproval->id }}"
                                                                    data-student-email="{{ $preapproval->student_email }}">
                                                                    <span class="mdi mdi-pencil-outline"></span>
                                                                </button>
                                                            </td>
                                                            <td class="pb-2">
                                                            <form class="form form-sample" method="POST" action="{{ route('preapprovedemail.delete') }}" onsubmit="return confirm('Are you sure you want to delete this email?')">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $preapproval->id }}">
                                                                <button type="submit" class="btn btn-danger btn-circle active"><span class="mdi mdi-delete btn-icon"></span></button>
                                                            </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add pre-approved emails form for teachers -->
                                <div class="card card-shadow mt-2">
                                    <div class="card-body m-0">
                                        <h5 class="card-title m-0">Allow New Student</h5>
                                        <form class="forms-sample" method="POST" action="{{ route('preapprovedemail.add')}}">
                                            @csrf
                                            <div class="row py-0">
                                                <div class="col-md-10 form-group mx-1">
                                                    <label for="student_email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" id="student_email" name="student_email" required>
                                                </div>
                                                <div class="col-md-1 form-group text-center mt-4">
                                                <input type="hidden" id="course_id" name="course_id" value="{{ $course->id }}" />
                                                    <button type="submit" class="btn btn-primary active">Add Email</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Update pre-approved emails form for teachers -->                                
                                <div class="card card-shadow mt-2" id="editCard" style="display: none;">
                                    <div class="card-body">
                                        <h5 class="card-title">Update Email</h5>
                                        <form class="forms-sample" action="{{ route('preapprovedemail.update') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="update_id" value="">
                                            <div class="form-group">
                                                <label for="student_email">Email</label>
                                                <input type="text" class="form-control" id="update_student_email" name="student_email" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary active">Update Email</button>
                                        </form>
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
            order: [[0, 'asc']]
        });

        let studentTable = new DataTable('#student-table', {
            lengthMenu: [5, 10, 15, 20, 50, 100],
            pageLength: 10,
        });

        let preapprovedEmailsTable = new DataTable('#preapproved-emails-table', {
            lengthMenu: [5, 10, 15, 20, 50, 100],
            pageLength: 10,
        });

        $(document).ready(function () {
            var hash = window.location.hash;
           
            if (hash !== '' && $(hash).length) {
                // Show the corresponding tab pane
                $('.tab-pane').removeClass('show active');
                $('.nav-link').removeClass('active');
                $(hash).addClass('show active');
                $(hash+"-nav").addClass('active');
            }

            // Listen for hash changes and switch tab panes accordingly
            $(window).on('hashchange', function() {
                var newHash = window.location.hash;

                if (newHash !== '' && $(newHash).length) {
                   
                    $('.tab-pane').removeClass('show active');
                    $('.nav-link').removeClass('active'); 
                    $(newHash).addClass('show active'); 
                    $(newHash+"-nav").addClass('active');
                }
            });

            // Handle the click event on the edit icon
            $('.edit-icon').click(function () {
                var id = $(this).data('id');
                var student_email = $(this).data('student-email');
                

                // Populate the form fields with the email data
                $('#update_id').val(id);
                $('#update_student_email').val(student_email);
                
                
                // Show the hidden card with the form and focus
                $('#editCard').show();
                $('#update_student_email').focus();
            });
        });
    </script>
</x-app-layout>
