@extends('layouts.winbox')
@prepend('page-css')
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endprepend
@section('content')
    <div class="container-fluid mb-0 pb-0">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>{{ $subject->subject->description }}</h3>
                    <span>Assigned Faculty: <strong> {{ $subject->faculty?->fullname }}</strong></span>
                </div>
                <div class="col-sm-6 text-end">
                    <button type="button" class="btn btn-success btn-add-student" data-bs-toggle="modal"
                        data-bs-target="#addStudent">
                        <i class="fas fa-plus-circle"></i> &nbsp; Add Student
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importStudents">
                        <i class="fas fa-upload"></i> &nbsp; Import Students
                    </button>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <table class="table table-condensed table-hovered" id="record-table" width="100%">
            <thead>
                <tr class="table-secondary">
                    <th width="5%">#</th>
                    <th>Student Name</th>
                    <th class="text-center">Course</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td class="align-middle">{{ $loop->index + 1 }}</td>
                        <td class="align-middle">{{ $student->fullname }}</td>
                        <td class="text-center text-uppercase align-middle">
                            {{ $student->course }} - {{ $student->year_level }}{{ $student->section }}
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-info btn-sm btn-square btn-edit-student" data-key="{{ $student->id }}"
                                data-bs-toggle="modal" data-bs-target="#addStudent"><i class="bx bx-pencil"></i></button>
                            <button class="btn btn-danger btn-sm btn-square btn-delete-student"
                                data-key="{{ $student->id }}" data-subject-id="{{ $subject->id }}"><i
                                    class="bx bx-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal for Importing Students -->
        <div class="modal fade modal-bookmark" id="importStudents" role="dialog" aria-labelledby="importStudentsLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importStudentsLabel">Import Students
                        </h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('students.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                            <div>
                                <label for="fileUpload" class="form-label">Import Students Data</label>
                                <input class="form-control form-control-lg" id="fileUpload" type="file" name="file">
                            </div>
                            <div class="mb-2 col-md-12 mt-2">
                                <label for="con-mail">Course</label>
                                {!! Form::text('course', null, [
                                    'placeholder' => 'Input Course',
                                    'class' => 'form-control course',
                                    'required',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="course-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Year Level</label>
                                <select class="form form-select year_level" name="year_level">
                                    <option disabled selected value="0">Select One</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                    <option value="5">5th Year</option>
                                </select>
                                <div class='text-danger' id="year_level-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Section</label>
                                {!! Form::text('section', null, [
                                    'placeholder' => 'section',
                                    'class' => 'form-control',
                                    'required',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="section-error-message"></div>
                            </div>

                            <div class="float-end mt-3">
                                <button type="submit" class='btn btn-primary mt-1 mb-2 lead'>Extract to Database</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Adding Students -->
        <div class="modal fade modal-bookmark" id="addStudent" role="dialog" aria-labelledby="addStudentLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header p-3">
                        <h5 class="modal-title" id="addStudentLabel">Add Students</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addStudent-form">
                            @csrf
                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                            <div class="row">
                                <div class="mb-2 col-md-4 mt-0">
                                    <label for="con-mail">First Name</label>
                                    {!! Form::text('first_name', null, [
                                        'placeholder' => 'First Name',
                                        'class' => 'form-control first_name',
                                        'required',
                                        'id' => 'first_name',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                <div class="mb-2 col-md-2 mt-0">
                                    <label for="con-mail">Middle Initial</label>
                                    {!! Form::text('middle_name', null, [
                                        'placeholder' => 'M.I.',
                                        'class' => 'form-control middle_name',
                                        'required',
                                        'id' => 'middle_name',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                <div class="mb-2 col-md-4 mt-0">
                                    <label for="con-mail">Last Name</label>
                                    {!! Form::text('last_name', null, [
                                        'placeholder' => 'Last Name',
                                        'class' => 'form-control last_name',
                                        'required',
                                        'id' => 'last_name',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                                <div class="mb-2 col-md-2 mt-0">
                                    <label for="con-mail">Suffix</label>
                                    {!! Form::text('suffix', null, [
                                        'placeholder' => 'Suffix',
                                        'class' => 'form-control suffix',
                                        'required',
                                        'id' => 'suffix',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                    <div class='text-danger' id="suffix-error-message"></div>
                                </div>
                                <div class="mb-2 col-md-4 mt-0">
                                    <label for="con-mail">Course</label>
                                    {!! Form::text('course', null, [
                                        'placeholder' => 'Input Course',
                                        'class' => 'form-control course',
                                        'required',
                                        'id' => 'course',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                    <div class='text-danger' id="course-error-message"></div>
                                </div>
                                <div class="mb-2 col-md-4 mt-0">
                                    <label for="con-mail">Year Level</label>
                                    <select class="form form-select year_level" name="year_level" id="year_level">
                                        <option disabled selected value="0">Select One</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                        <option value="5">5th Year</option>
                                    </select>
                                    <div class='text-danger' id="year_level-error-message"></div>
                                </div>
                                <div class="mb-2 col-md-4 mt-0">
                                    <label for="con-mail">Section</label>
                                    {!! Form::text('section', null, [
                                        'placeholder' => 'Section',
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'section',
                                        'autocomplete' => 'off',
                                    ]) !!}
                                    <div class='text-danger' id="section-error-message"></div>
                                </div>
                            </div>
                            <div class="float-end mt-3">
                                <button type="button" class='btn btn-primary mt-1 mb-2 lead' id="btnSaveStudent">Add
                                    Student to this Subject</button>
                                <button class="btn btn-secondary d-none" type="button" id="btnSaveStudentChanges">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
        <script>
            $(function() {
                var table = $("table").dataTable({
                    "dom": "<'pull-left'f>",
                    paging: false,
                    ordering: false,
                });

                $('.btn-add-student').on('click', function() {
                    $("#addStudent-form")[0].reset();
                    $("#btnSaveStudent").removeClass('d-none');
                    $("#btnSaveStudentChanges").addClass('d-none');
                });

                $('#btnSaveStudent').click(function(e) {
                    e.preventDefault();
                    let data = $("#addStudent-form").serialize();
                    $.ajax({
                        url: `/student`,
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success == true) {
                                swal({
                                    text: 'Successfully added!',
                                    icon: 'success',
                                    timer: 2500,
                                    buttons: false,
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                swal({
                                    text: `${response.error}`,
                                    icon: 'warning',
                                    timer: 2500,
                                    buttons: false,
                                });
                            }
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                let errors = response.responseJSON.errors;
                                const inputNames = [
                                    "first_name",
                                    "middle_name",
                                    "last_name",
                                    "course",
                                    "year_level",
                                    "section",
                                ];
                                $.each(inputNames, function(index, value) {
                                    if (errors.hasOwnProperty(value)) {
                                        $(`.${value}`).addClass("is-invalid");
                                    } else {
                                        $(`.${value}`).removeClass("is-invalid");
                                    }
                                });

                            }
                        },
                    });
                });

                $('.btn-edit-student').on('click', function() {
                    const id = $(this).attr('data-key');
                    $("#btnSaveStudent").addClass('d-none');
                    $("#btnSaveStudentChanges").removeClass('d-none').attr('data-key', id);

                    $.ajax({
                        url: `/student/${id}`,
                        success: function(record) {
                            $("#first_name").val(record.first_name);
                            $("#middle_name").val(record.middle_name);
                            $("#last_name").val(record.last_name);
                            $("#suffix").val(record.suffix);
                            $("#course").val(record.course);
                            $("#year_level").val(record.year_level);
                            $("#section").val(record.section);
                        },
                    });
                });

                $('#btnSaveStudentChanges').click(function(e) {
                    e.preventDefault();
                    const id = $(this).attr('data-key');
                    let data = $("#addStudent-form").serialize();
                    $.ajax({
                        url: `/student/${id}`,
                        method: 'PUT',
                        data: data,
                        success: function(response) {
                            if (response.success == true) {
                                swal({
                                    text: 'Successfully updated!',
                                    icon: 'success',
                                    timer: 2500,
                                    buttons: false,
                                });
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function(response) {
                            if (response.status === 422) {
                                let errors = response.responseJSON.errors;
                                const inputNames = [
                                    "first_name",
                                    "middle_name",
                                    "last_name",
                                    "course",
                                    "year_level",
                                    "section",
                                ];
                                $.each(inputNames, function(index, value) {
                                    if (errors.hasOwnProperty(value)) {
                                        $(`.${value}`).addClass("is-invalid");
                                    } else {
                                        $(`.${value}`).removeClass("is-invalid");
                                    }
                                });

                            }
                        },
                    });
                });

                $('.btn-delete-student').on('click', function() {
                    const student_id = $(this).attr('data-key');
                    const subject_id = $(this).attr('data-subject-id');
                    swal({
                        text: "Are you sure you want to delete this?",
                        icon: "warning",
                        buttons: [
                            'No',
                            'Yes!'
                        ],
                        dangerMode: true,
                        closeOnClickOutside: false,
                    }).then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                url: `/student/${student_id}/${subject_id}`,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.success == true) {
                                        swal({
                                            text: 'Successfully deleted!',
                                            icon: 'success',
                                            timer: 1500,
                                            buttons: false,
                                        });
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1500);
                                    }
                                },
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
