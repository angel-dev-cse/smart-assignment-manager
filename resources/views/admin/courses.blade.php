<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Add New Course</h5>
                                <form class="forms-sample" action="{{ route('course.store')}}" method="post">
                                    @csrf
                                    <div class="form-group m-0">
                                        <label for="course_name">Course Name</label>
                                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                                    </div>
                                    <div class="form-group m-0">
                                        <label for="course_code">Course Code</label>
                                        <input type="text" class="form-control" id="course_code" name="course_code" required>
                                    </div>
                                    <div class="form-group m-0">
                                        <label for="semester">Semester</label>
                                        <select id="semester" name="semester" class="form-control" required>
                                            <option value="1">1st Semester</option>  
                                            <option value="2">2nd Semester</option>  
                                            <option value="3">3rd Semester</option>  
                                            <option value="4">4th Semester</option>  
                                            <option value="5">5th Semester</option>  
                                            <option value="6">6th Semester</option>  
                                            <option value="7">7th Semester</option>  
                                            <option value="8">8th Semester</option>  
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="department_id" name="department_id" required>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary active">Add Course</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Courses</h5>
                                <div class="table-responsive">
                                    <table id="courses-table" class="table nowrap-table dataTable">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="sorting sorting_asc" tabindex="0" aria-controls="courses-table">#</th>
                                                <th class="sorting" tabindex="0" aria-controls="courses-table">Course Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="courses-table">Course Code</th>
                                                <th class="sorting" tabindex="0" aria-controls="courses-table">Semester</th>
                                                <th class="sorting" tabindex="0" aria-controls="courses-table">Department</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($courses as $key => $course)
                                            <tr class="text-center">
                                                <td class="sorting_1">{{ $key + 1 }}</td>
                                                <td class="text-capitalize"><b>{{ $course->course_name }}</b></td>
                                                <td>{{ $course->course_code }}</td>
                                                <td>{{ $course->semester }}</td>
                                                <td>{{ $course->department->department_name }}</td>
                                                <td class="pb-2">
                                                    <button class="btn btn-circle btn-primary edit-icon mx-2"
                                                        data-id="{{ $course->id }}"
                                                        data-name="{{ $course->course_name }}"
                                                        data-code="{{ $course->course_code }}"
                                                        data-semester="{{ $course->semester }}"
                                                        data-department="{{ $course->department_id }}">
                                                        <span class="mdi mdi-pencil-outline"></span>
                                                    </button>
                                                </td>
                                                <td class="pb-2">
                                                <form class="form form-sample" method="POST" action="{{ route('course.delete') }}" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                                    @csrf
                                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 mt-3 mx-24" id="editCard" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Edit Course</h5>
                                <form class="forms-sample" action="{{ route('course.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="course_id" id="update_course_id" value="">
                                    <div class="form-group">
                                        <label for="course_name">Course Name</label>
                                        <input type="text" class="form-control" id="update_course_name" name="course_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="course_code">Course Code</label>
                                        <input type="text" class="form-control" id="update_course_code" name="course_code" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="semester">Semester</label>
                                        <select id="update_semester" name="semester" class="form-control" required>
                                            <option value="1">1st Semester</option>  
                                            <option value="2">2nd Semester</option>  
                                            <option value="3">3rd Semester</option>  
                                            <option value="4">4th Semester</option>  
                                            <option value="5">5th Semester</option>  
                                            <option value="6">6th Semester</option>  
                                            <option value="7">7th Semester</option>  
                                            <option value="8">8th Semester</option>  
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="update_department_id" name="department_id" required>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary active">Update Course</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Handle the click event on the edit icon
        $('.edit-icon').click(function () {
            var course_id = $(this).data('id');
            var course_name = $(this).data('name');
            var course_code = $(this).data('code');
            var semester = $(this).data('semester');
            var department_id = $(this).data('department');

            // Populate the form fields with the course data
            $('#update_course_id').val(course_id);
            $('#update_course_name').val(course_name);
            $('#update_course_code').val(course_code);
            $('#update_semester').val(semester);
            $('#update_department_id').val(department_id);
            
            // Show the hidden card with the form anf focus
            $('#editCard').show();
        $('#update_department_id').focus();
        });
    });

    let coursesTable = new DataTable("#courses-table", {
        lengthMenu:[5,10,15,20,50,100],
        pageLength: 5,
    });
</script>
</x-app-layout>



