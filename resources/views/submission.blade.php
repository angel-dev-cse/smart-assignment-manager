<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <!-- Display assignment details in a card on top of the page -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $assignment->topic }}</h5>
                        <p class="card-description">Marks: <b>{{ $assignment->marks }}</b> <br/> Due Date: <b>{{ \Carbon\Carbon::parse($assignment->deadline)->format('d F, Y') }}</b></p>
                        <p class="card-text"><b>Submitted by:</b> {{ $submission->student->user->name }}</p>
                        <p class="card-text"><b>ID:</b> {{ $submission->student->roll }}</p>
                        <p class="card-text inline"><b>Status: </b></p>
                        @if ($submission->status === 'pending')
                            <p class="badge badge-opacity-warning">{{ $submission->status }}</p>
                        @elseif ($submission->status === 'declined')
                            <p class="badge badge-danger">{{ $submission->status }}</p>
                            <br/>
                            <p class="card-text inline"><b>Score: {{ $submission->score }}</b></p>
                        @else
                            <p class="badge badge-opacity-success">{{ $submission->status }}</p>
                            <br/>
                            <p class="card-text inline"><b>Score: {{ $submission->score }}</b></p>
                        @endif
                        <br/>
                        <br/>
                        <h2 class="card-text">{{ $submission->description }}</h2>
                        <div class="row mt-3 rounded">
                            <!-- Display the attached file if available -->
                            @if ($submission->file_path)
                                @php
                                    // Get the file extension
                                    $fileExtension = pathinfo($submission->file_path, PATHINFO_EXTENSION);
                                @endphp
                                <div class="col-md-9">
                                    @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <!-- Display image using img tag -->
                                        
                                            <img class="rounded d-block" style="max-height: 600px" src="{{ asset('storage/' . $submission->file_path) }}" alt="Attached Image" />
                                        
                                        
                                    @elseif ($fileExtension === 'pdf')
                                        <!-- Display PDF using pdf.js -->
                                        <div id="pdf-viewer">
                                            <iframe src="{{ asset('storage/' . $submission->file_path) }}" width="100%" height="600px"></iframe>
                                        </div>
                                    @else
                                        <!-- Display other file types using Google Docs Viewer -->
                                        <iframe src="https://docs.google.com/viewer?url={{ asset('storage/' . $submission->file_path) }}&embedded=true" width="100%" height="600px"></iframe>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" download>
                                        <button class="btn btn-labeled btn-success"><span class="mdi btn-label mdi-download"></span>Download</button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Review</h5>
                        <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Review</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td>
                                            <textarea class="form-control" style="min-height:8rem;width:40rem;" id="review" name="review" disabled>{{ $submission->review }}</textarea>
                                        </td>
                                        <td>{{$submission->score}}</td>
                                        @if ($submission->status === 'pending')
                                            <td><label class="badge badge-opacity-warning">{{ $submission->status }}</label></td>
                                        @elseif ($submission->status === 'declined')
                                            <td><label class="badge badge-danger">{{ $submission->status }}</label></td>
                                        @else
                                            <td><label class="badge badge-opacity-success">{{ $submission->status }}</label></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>

                <!-- Show the review form for the teacher -->
                @if (Auth::user()->hasRole('teacher'))
                    @if($submission->status==='pending')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reviewing as <b>{{ Auth::user()->name }}</b></h5>
                            <form class="row forms-sample" action="{{ route('review.submit') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="description">Review</label>
                                    <textarea class="form-control" style="height:5rem" id="review" name="review" required></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="score">Score (Out of {{ $assignment->marks }})</label>
                                    <input type="number" class="form-control" id="score" name="score" placeholder="0" max="{{ $assignment->marks }}" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="status">Status</label><br>
                                    <div class="form-check form-check-radio">
                                        <input class="form-check-input" type="radio" name="status" id="accept" value="approved" checked>
                                        <label class="form-check-label" for="accept">Accept Submisison</label>
                                    </div>
                                    <div class="form-check form-check-radio">
                                        <input class="form-check-input" type="radio" name="status" id="reject" value="declined">
                                        <label class="form-check-label" for="reject">Reject Submisison</label>
                                    </div>
                                </div>
                                <input type="hidden" id="submission_id" name="submission_id" value="{{ $submission->id }}" required>
                                <div class="form-group col-md-6">
                                    <input class="btn btn-primary active" type="submit" value="Submit Review"/>
                                </div>
                            </form>
                        </div>
                    </div>

                    @else
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Update Review</b></h5>
                                <form class="row forms-sample" action="{{ route('review.submit') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group col-md-6">
                                        <label for="description">Review</label>
                                        <textarea class="form-control" style="height:10rem" id="review" name="review"required>{{ $submission->review }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="score">Score (Out of {{ $assignment->marks }})</label>
                                        <input type="number" class="form-control" id="score" name="score" placeholder="0" max="{{ $assignment->marks }}" value="{{ $submission->score }}" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="status">Status</label><br>
                                        <div class="form-check form-check-radio">
                                            <input class="form-check-input" type="radio" name="status" id="accept" value="approved" checked>
                                            <label class="form-check-label" for="accept">Accept Submisison</label>
                                        </div>
                                        <div class="form-check form-check-radio">
                                            <input class="form-check-input" type="radio" name="status" id="reject" value="declined">
                                            <label class="form-check-label" for="reject">Reject Submisison</label>
                                        </div>
                                    </div>
                                    <input type="hidden" id="submission_id" name="submission_id" value="{{ $submission->id }}" required>
                                    <div class="form-group col-md-6">
                                        <input class="btn btn-primary active" type="submit" value="Update Review"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif               
                @endif

                <!-- Show the update form to the student -->
                @if (Auth::user()->hasRole('student'))
                    @if($submission->status==='pending')
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Update your submission</h5>
                                <form class="forms-sample" action="{{ route('submission.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" style="height:8rem" id="description" name="description" required>{{ $submission->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="submission_file">File</label>
                                        <input type="file" class="form-control" id="file" name="file" required>
                                    </div>
                                    <input type="hidden" id="submission_id" name="submission_id" value="{{ $submission -> id }}" required>
                                    <input class="btn btn-primary active" type="submit" value="Update Submission"/>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
        </div>    
    </div>
    <!-- Add the Viewer.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.12.0/viewer.min.js"></script>

    <!-- Initialize the Viewer.js plugin -->
    <script>
        // Wrap the Viewer.js initialization inside a DOMContentLoaded event to ensure the DOM is ready
        document.addEventListener("DOMContentLoaded", function () {
            // Get the file viewer element
            var viewer = new Viewer(document.getElementById("file-viewer"), {
                navbar: false, // Hide the navigation bar
                title: false, // Hide the file name on top of the viewer
            });
        });
    </script>
</x-app-layout>
