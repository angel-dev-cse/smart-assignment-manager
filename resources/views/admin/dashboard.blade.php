<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />

        <div class="main-panel">
            <div class="content-wrapper">
                <div class="m-0">
                    <div class="">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-pill">
                                    <button class="btn btn-primary btn-sm">
                                        <p class="mb-0">Total Students: {{ $studentCount}}</p>
                                    </button>
                                </div>

                                <div class="col-pill">
                                    <button class="btn btn-success btn-sm">
                                        <p class="mb-0">Total Teachers: {{ $teacherCount}}</p>
                                        <!-- Display total teachers count here -->
                                    </button>
                                </div>

                                <div class="col-pill">
                                    <button class="btn btn-info btn-sm">
                                        <p class="mb-0">Total Courses: {{ $courseCount}}</p>
                                        <!-- Display total courses count here -->
                                    </button>
                                </div>

                                <div class="col-pill">
                                    <button class="btn btn-dark btn-sm">
                                        <p class="mb-0">Total Departments: {{ $departmentCount}}</p>
                                        <!-- Display total departments count here -->
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <a href="{{ route('registration.index') }}" class="card card-hover bg-info">
                                    <div class="card-body">
                                        <img class="card-img-top" src="startheme/images/registrations.png" alt="Card image cap">
                                        <h5 class="card-title">Pending Registration Verifications</h5>
                                        <p class="card-text">Manage pending student and teacher registrations</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 mb-4">
                                <a href="{{ route('application.index') }}" class="card card-hover bg-info">
                                    <div class="card-body">
                                        <img class="card-img-top" src="startheme/images/applications.png" alt="Card image cap">
                                        <h5 class="card-title">Pending Enrollments</h5>
                                        <p class="card-text">Manage pending student and teacher enrollments</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 mb-4">
                                <a href="{{ route('course.index') }}" class="card card-hover bg-warning">
                                    <div class="card-body">
                                       <img class="card-img-top" src="startheme/images/courses.png" alt="Card image cap">
                                        <h5 class="card-title">Manage Courses</h5>
                                        <p class="card-text">Manage existing courses, add new courses</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-md-3 mb-4">
                                <a href="{{ route('department.index') }}" class="card card-hover bg-warning">
                                    <div class="card-body">
                                        <img class="card-img-top" src="startheme/images/departments.png" alt="Card image cap">
                                        <h5 class="card-title">Manage Departments</h5>
                                        <p class="card-text">Manage existing departments</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
