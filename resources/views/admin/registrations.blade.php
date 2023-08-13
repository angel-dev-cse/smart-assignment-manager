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
                                            Pending Student Registrations
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" href="#assignments" role="tab" aria-controls="assignments" aria-selected="false">
                                            Pending Teacher Registrations
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content card card-shadow" id="myTabContent">
                                <div class="tab-pane fade show active" id="courses" role="tabpanel" aria-labelledby="courses-tab">
                                    <!-- Display enrollments here -->
                                        <div class="table-responsive nowrap-table m-2">
                                            <table id="student-registrations-table" class="table table-hover dataTable">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting sorting_asc text-center" tabindex="0" aria-controls="student-registrations-table">No.</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Name</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Email</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Roll</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Semester</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Session</th>
                                                        <th class="sorting text-center" tabindex="0" aria-controls="student-registrations-table">Department</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($studentRegistrations as $key => $registration)
                                                            <tr class="text-center text-break">
                                                                <td class="sorting_1">{{ $key + 1 }}</td>
                                                                <td class="text-capitalize"><b>{{ $registration->name }}</b></td>
                                                                <td>{{ $registration->email }}</td>
                                                                <td>{{ $registration->student->roll }}</td>
                                                                <td>{{ $registration->student->semester }}</td>
                                                                <td>{{ $registration->student->session }}</td>
                                                                <td>{{ $registration->student->department->department_name }}</td>
                                                                <td>
                                                                    <form class="form" method="POST" action="{{ route('registration.verify')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="id" id="id" value="{{$registration->id}}" hidden/>
                                                                        <input type="hidden" name="status" id="status" value="approved" hidden/>
                                                                        <button class="btn btn-success" name="submit" id="submit"><i class="mdi mdi-check-outline"></i></button></a></td>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <form class="form" method="POST" action="{{ route('registration.verify')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="id" id="id" value="{{$registration->id}}" hidden/>
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
                                    <div class="table-responsive nowrap-table m-2">
                                        <table id="teacher-registrations-table" class="table table-hover dataTable">
                                            <thead>
                                                <tr>
                                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="teacher-registrations-table">No.</th>
                                                    <th class="sorting text-center" tabindex="0" aria-controls="teacher-registrations-table">Name</th>
                                                    <th class="sorting text-center" tabindex="0" aria-controls="teacher-registrations-table">Email</th>
                                                    <th class="sorting text-center" tabindex="0" aria-controls="teacher-registrations-table">Qualification</th>
                                                    <th class="sorting text-center" tabindex="0" aria-controls="teacher-registrations-table">Department</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teacherRegistrations as $key => $registration)
                                                    <tr class="text-center">
                                                        <td class="sorting_1">{{ $key + 1 }}</td>
                                                        <td class="text-capitalize"><b>{{ $registration->name }}</b></td>
                                                        <td>{{ $registration->email }}</td>
                                                        <td class="mx-24">{{ $registration->teacher->qualification }}</td>
                                                        <td>{{ $registration->teacher->department->department_name }}</td>
                                                        <td>
                                                            <form class="form" method="POST" action="{{ route('registration.verify')}}">
                                                                @csrf
                                                                <input type="hidden" name="id" id="id" value="{{$registration->id}}" hidden/>
                                                                <input type="hidden" name="status" id="status" value="approved" hidden/>
                                                                <button class="btn btn-success" name="submit" id="submit"><i class="mdi mdi-check-outline"></i></button></a></td>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <form class="form" method="POST" action="{{ route('registration.verify')}}">
                                                                @csrf
                                                                <input type="hidden" name="id" id="id" value="{{$registration->id}}" hidden/>
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
    <script>
        let studentRegistrationsTable = new DataTable("#student-registrations-table", {
            lengthMenu: [10, 20, 50, 100],
            pageLength: 10,
            order: [[0, 'asc']]
        });

        let teacherRegistrationsTable = new DataTable("#teacher-registrations-table", {
            lengthMenu: [10, 20, 50, 100],
            pageLength: 10,
            order: [[0, 'asc']]
        });
    </script>
</x-app-layout>
