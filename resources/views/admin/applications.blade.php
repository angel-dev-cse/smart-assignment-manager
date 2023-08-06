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
                                        <button class="nav-link active ps-0" id="courses-tab" data-bs-toggle="tab" href="#courses" role="tab" aria-controls="courses" aria-selected="true">
                                            Pending Student Enrollments
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">
                                            Pending Teacher Enrollments
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content card" id="myTabContent">
                                <div class="tab-pane fade show active" id="courses" role="tabpanel" aria-labelledby="courses-tab">
                                    <!-- Display enrollments here -->
                                        <div class="table-responsive nowrap-table">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>No.</th>
                                                        <th>Name</th>
                                                        <th>Roll</th>
                                                        <th>Semester</th>
                                                        <th>Department</th>
                                                        <th>Course Name</th>
                                                        <th>Course Code</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($enrollments as $key => $enrollment)
                                                            <tr class="text-center">
                                                                <td>{{ $key + 1 }}</td>
                                                                <td class="text-capitalize"><b>{{ $enrollment->student->user->name }}</b></td>
                                                                <td>{{ $enrollment->student->roll }}</td>
                                                                <td>{{ $enrollment->student->semester }}</td>
                                                                <td>{{ $enrollment->course->department->department_name }}</td>
                                                                <td>{{ $enrollment->course->course_name }}</td>
                                                                <td>{{ $enrollment->course->course_code }}</td>
                                                                <td>
                                                                    <form class="form" method="POST" action="{{ route('enrollment.verify')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="id" id="id" value="{{$enrollment->id}}" hidden/>
                                                                        <input type="hidden" name="status" id="status" value="approved" hidden/>
                                                                        <button class="btn btn-success" name="submit" id="submit"><i class="mdi mdi-check-outline"></i></button></a></td>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <form class="form" method="POST" action="{{ route('enrollment.verify')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="id" id="id" value="{{$enrollment->id}}" hidden/>
                                                                        <input type="hidden" name="status" id="status" value="declined" hidden/>
                                                                        <button class="btn btn-danger" name="submit" id="submit"><i class="mdi mdi-close-outline"></i></button></a></td>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                </div>

                                <div class="tab-pane fade" id="assignments" role="tabpanel" aria-labelledby="assignments-tab">
                                    <!-- Display teaches here -->
                                    <div class="table-responsive nowrap-table">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>Qualification</th>
                                                    <th>Department</th>
                                                    <th>Course Name</th>
                                                    <th>Course Code</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teaches as $key => $teach)
                                                        <tr class="text-center">
                                                            <td>{{ $key + 1 }}</td>
                                                            <td class="text-capitalize"><b>{{ $teach->teacher->user->name }}</b></td>
                                                            <td>{{ $teach->teacher->qualification }}</td>
                                                            <td>{{ $teach->teacher->department->department_name }}</td>
                                                            <td>{{ $teach->course->course_name }}</td>
                                                            <td>{{ $teach->course->course_code }}</td>
                                                            <td>
                                                                <form class="form" method="POST" action="{{ route('teach.verify')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="id" id="id" value="{{$teach->id}}" hidden/>
                                                                    <input type="hidden" name="status" id="status" value="approved" hidden/>
                                                                    <button class="btn btn-success" name="submit" id="submit"><i class="mdi mdi-check-outline"></i></button></a></td>
                                                                </form>
                                                            </td>
                                                            <td>
                                                                <form class="form" method="POST" action="{{ route('teach.verify')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="id" id="id" value="{{$teach->id}}" hidden/>
                                                                    <input type="hidden" name="status" id="status" value="declined" hidden/>
                                                                    <button class="btn btn-danger" name="submit" id="submit"><i class="mdi mdi-close-outline"></i></button></a></td>
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
    </div>
</x-app-layout>
