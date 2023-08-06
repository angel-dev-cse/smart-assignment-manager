<x-app-layout>
    <div class="container-fluid page-body-wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Add New Department</h5>
                                <form class="forms-sample" action="{{ route('department.store')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="department_name">Department Name</label>
                                        <input type="text" class="form-control" id="department_name" name="department_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" style="min-height:6rem" id="description" name="description" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary active">Add Department</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Departments</h5>
                                <div class="table-responsive">
                                    <table id="departments-table" class="table nowrap-table dataTable">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="sorting sorting_asc" tabindex="0" aria-controls="departments-table">#</th>
                                                <th class="sorting" tabindex="0" aria-controls="departments-table">Department Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="departments-table">Description</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($departments as $key => $department)
                                            <tr class="text-center">
                                                <td class="sorting_1">{{ $key + 1 }}</td>
                                                <td class="text-capitalize"><b>{{ $department->department_name }}</b></td>
                                                <td class="text-truncate">{{ $department->description }}</td>
                                                <td class="pb-2">
                                                    <button class="btn btn-circle btn-primary edit-icon mx-2"
                                                        data-id="{{ $department->id }}"
                                                        data-name="{{ $department->department_name }}"
                                                        data-description="{{ $department->description }}">
                                                        <span class="mdi mdi-pencil-outline"></span>
                                                    </button>
                                                </td>
                                                <td class="pb-2">
                                                <form class="form form-sample" method="POST" action="{{ route('department.delete') }}" onsubmit="return confirm('Are you sure you want to delete this department?')">
                                                    @csrf
                                                    <input type="hidden" name="department_id" value="{{ $department->id }}">
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
                                <h5 class="card-title">Edit Department</h5>
                                <form class="forms-sample" action="{{ route('department.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="department_id" id="update_department_id" value="">
                                    <div class="form-group">
                                        <label for="department_name">Department Name</label>
                                        <input type="text" class="form-control" id="update_department_name" name="department_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" style="min-height:6rem" id="update_description" name="description" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary active">Update Department</button>
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
            var department_id = $(this).data('id');
            var department_name = $(this).data('name');
            var description = $(this).data('description');

            // Populate the form fields with the department data
            $('#update_department_id').val(department_id);
            $('#update_department_name').val(department_name);
            $('#update_description').val(description);
            
            // Show the hidden card with the form anf focus
            $('#editCard').show();
            $('#update_description').focus();
        });
    });

    let departmentsTable = new DataTable("#departments-table", {
        lengthMenu:[5,10,15,20,50,100],
        pageLength:5,
    });
</script>
</x-app-layout>



