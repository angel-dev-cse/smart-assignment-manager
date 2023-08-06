<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <!-- Display assignment details in a card on top of the page -->
                <div class="card border mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $assignment->topic }}</h5>
                        <p class="card-description">Marks: <b>{{ $assignment->marks }}</b> <br/> Due Date: <b>{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</b></p>
                        <h2 class="card-text"><b>Instructions:</b> {{ $assignment->description }}</h2>
                        <div class="row mt-3 rounded">
                            <!-- Display the attached file if available -->
                            @if ($assignment->file_path)
                                @php
                                    // Get the file extension
                                    $fileExtension = pathinfo($assignment->file_path, PATHINFO_EXTENSION);
                                @endphp
                                <div class="col-md-9">
                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <!-- Display image using img tag -->
                                        
                                            <img class="rounded d-block" style="max-height: 600px" src="{{ asset('storage/' . $assignment->file_path) }}" alt="Attached Image" />
                                        
                                        
                                    @elseif ($fileExtension === 'pdf')
                                        <!-- Display PDF using pdf.js -->
                                        <div id="pdf-viewer">
                                            <iframe src="{{ asset('storage/' . $assignment->file_path) }}" width="100%" height="600px"></iframe>
                                        </div>
                                    @else
                                        <!-- Display other file types using Google Docs Viewer -->
                                        <iframe src="https://docs.google.com/viewer?url={{ asset('storage/' . $assignment->file_path) }}&embedded=true" width="100%" height="600px"></iframe>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ asset('storage/' . $assignment->file_path) }}" download>
                                        <button class="btn btn-labeled btn-success"><span class="mdi btn-label mdi-download"></span>Download</button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Show the submission form for students -->
                @if (Auth::user()->hasRole('student'))
                    @if(!$assignment->hasSubmitted(Auth::user()->student->id))
                        @if($assignment->daysRemaining() > -1)
                            <div class="card border border-primary mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Submit Your Assignment</h5>
                                    <form class="forms-sample" action="{{ route('submission.submit') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" style="height:8rem" id="description" name="description" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="submission_file">File</label>
                                            <input type="file" class="form-control" id="file" name="file" required>
                                        </div>
                                        <input type="hidden" id="assignment_id" name="assignment_id" value="{{ $assignment -> id }}" required>
                                        <input class="btn btn-primary active" type="submit" value="Submit"/>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="card border border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Your Submisison</h5>
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Submitted On</th>
                                            <th>Status</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ \Carbon\Carbon::parse($submission->created_at)->format('d F, Y') }}</td>
                                            @if ($submission->status === 'pending')
                                                <td><label class="badge badge-opacity-warning">{{ $submission->status }}</label></td>
                                            @elseif ($submission->status === 'declined')
                                                <td><label class="badge badge-danger">{{ $submission->status }}</label></td>
                                            @else
                                                <td><label class="badge badge-opacity-success">{{ $submission->status }}</label></td>
                                            @endif
                                            <td><a href="{{ route('submission.show', ['id' => $submission->id]) }}"><button class="btn btn-primary">View</button></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif               
                @endif

                <!-- Show the update assignment form for teachers -->
                @if (Auth::user()->hasRole('teacher'))
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border border-primary mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Update Assignment</h5>
                                    <form class="forms-sample" action="{{ route('assignment.update') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="topic" class="form-label">Topic</label>
                                            <input type="text" class="form-control" id="topic" name="topic" value="{{ $assignment->topic }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" style="height:8rem" id="description" name="description" required>{{ $assignment->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="file">File</label>
                                            <input type="file" class="form-control" id="file" name="file" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="marks" class="form-label">Marks</label>
                                            <input type="number" class="form-control" id="marks" name="marks" value="{{ $assignment->marks }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="deadline" class="form-label">Deadline</label>
                                            <input type="date" class="form-control" id="deadline" name="deadline" value="{{ \Carbon\Carbon::parse($assignment->created_at)->format('Y-m-d') }}" required>
                                        </div>
                                        <input type="hidden" id="assignment_id" name="assignment_id" value="{{ $assignment -> id }}" required>
                                        <input class="btn btn-primary active" type="submit" value="Update Assignment"/>
                                    </form>
                                    <form class="form form-sample mt-2" method="POST" action="{{ route('assignment.delete') }}" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                        @csrf
                                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                        <button type="submit" class="btn btn-danger active"><i class="mdi mdi-delete btn-icon"></i>Delete Assignment</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Show the submissions to the teacher -->
                        <div class="col-md-8">
                            <div class="card border border-primary mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Submissions</h5>
                                    <div class="table-responsive">
                                        <table id="submission-table" class="table table-hover dataTable nowrap-table">
                                            <thead>
                                                <tr>
                                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="submission-table">No</th>
                                                    <th class="sorting" tabindex="0" aria-controls="submission-table">Submitted By</th>
                                                    <th class="sorting" tabindex="0" aria-controls="submission-table">ID</th>
                                                    <th class="sorting" tabindex="0" aria-controls="submission-table">Submitted On</th>
                                                    <th class="sorting" tabindex="0" aria-controls="submission-table">Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($submissions as $key=>$submission)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{$submission->student->user->name}}</td>
                                                        <td>{{$submission->student->roll}}</td>
                                                        <td>{{ \Carbon\Carbon::parse($submission->created_at)->format('d F, Y') }}</td>
                                                        @if ($submission->status === 'pending')
                                                            <td><label class="badge badge-opacity-warning">{{ $submission->status }}</label></td>
                                                        @elseif ($submission->status === 'declined')
                                                            <td><label class="badge badge-danger">{{ $submission->status }}</label></td>
                                                        @else
                                                            <td><label class="badge badge-opacity-success">{{ $submission->status }}</label></td>
                                                        @endif
                                                        <td><a href="{{ route('submission.show', ['id' => $submission->id]) }}"><button class="btn btn-circle btn-primary"><span class="mdi mdi-eye"></span></button></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>    
    </div>
    <script>
        let table = new DataTable('#submission-table', {
            lengthMenu:[5,10,15,20]
        });
    </script>
</x-app-layout>
