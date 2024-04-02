@extends('layouts.app')
@prepend('page-css')
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <style>
        .dataTables_wrapper .myfilter .dataTables_filter {
            float: left
        }

        .dataTables_wrapper .mylength .dataTables_length {
            float: right
        }
    </style>
@endprepend
@section('content')
    <div class="row">
        <div class="col-xxl-12 col-xl-12 col-lg-12 box-col-12">
            <div class="card">
                <div class="card-header bg-transaparent border-primary border-bottom border-5 text-uppercase">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mt-2">Faculties</h3>
                        </div>
                        <div class="col-6 text-end">
                            <button type="button" class="btn btn-primary text-uppercase" id="btnAddNewRecord">
                                Add New Record
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <table class="table table-bordered w-100" id="dataTable">
                                <thead>
                                    <tr class="table-light">
                                        <th width="30%" class="text-truncate">Faculty Name</th>
                                        <th width="15%" class="text-truncate">Contact #</th>
                                        <th width="15%" class="text-truncate">Status</th>
                                        <th width="25%" class="text-truncate">Department</th>
                                        <th width="15%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="recordModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark ">
                    <span class="modal-title h4 text-uppercase text-white">
                        Add New Record
                    </span>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" id="btn-close-modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="recordForm">
                        @csrf
                        <div class="row">
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-name">Select Department</label>
                                <select class="form form-select department_id" name="department_id" id="department_id">
                                    <option value="">Search name here</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->short_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-mail">Contact #</label>
                                {!! Form::text('contact_no', null, [
                                    'placeholder' => 'Contact #',
                                    'class' => 'form-control contact_no',
                                    'required',
                                    'id' => 'contact_no',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="contact_no-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-mail">Years in Service</label>
                                {!! Form::text('years_in_service', null, [
                                    'placeholder' => 'Enter years in service',
                                    'class' => 'form-control years_in_service',
                                    'required',
                                    'id' => 'years_in_service',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="years_in_service-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-mail">Educational Qualification</label>
                                {!! Form::text('educational_qualification', null, [
                                    'placeholder' => 'Education',
                                    'class' => 'form-control educational_qualification',
                                    'required',
                                    'id' => 'educational_qualification',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="educational_qualification-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-mail">Major</label>
                                {!! Form::text('major', null, [
                                    'placeholder' => 'Major',
                                    'class' => 'form-control major',
                                    'required',
                                    'id' => 'major',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="major-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-mail">Eligibility</label>
                                {!! Form::text('eligibility', null, [
                                    'placeholder' => 'Eligibility/PRC',
                                    'class' => 'form-control eligibility',
                                    'required',
                                    'id' => 'eligibility',
                                    'autocomplete' => 'off',
                                ]) !!}
                                <div class='text-danger' id="eligibility-error-message"></div>
                            </div>
                            <div class="mb-2 col-md-6 mt-0">
                                <label for="con-name">Employment Status</label>
                                <select class="form form-select employment_status" name="employment_status"
                                    id="employment_status">
                                    <option selected value="Permanent">Permanent</option>
                                    <option value="Contractual">Contractual</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="float-right">
                        <button class="btn btn-primary btn-lg" id="btnSave">Save Record</button>
                        <button class="btn btn-primary btn-lg d-none" id="btnSaveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('page-scripts')
        <script src="{{ asset('/assets/libs/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/libs/winbox/winbox.bundle.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- toastr plugin -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
        <script src="{{ asset('/assets/js/axios.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                const inputNames = [
                    "department_id",
                    "first_name",
                    "middle_name",
                    "last_name",
                    "suffix",
                    "contact_no",
                    "employment_status",
                    "years_in_service",
                    "educational_qualification",
                    "major",
                    "eligibility",
                ];

                let table = $('#dataTable').DataTable({
                    serverSide: true,
                    processing: true,
                    destroy: true,
                    ordering: false,
                    dom: "<'myfilter'f><'mylength'l>tp",
                    language: {
                        processing: '<i class="text-primary fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                    },
                    ajax: `/faculty/list`,
                    columns: [{
                            class: 'align-middle text-start',
                            data: 'fullname',
                            name: 'fullname',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle',
                            data: 'contact_no',
                            name: 'contact_no',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'employment_status',
                            name: 'employment_status',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-start',
                            data: 'department',
                            name: 'department',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'actions',
                            name: 'actions',
                            searchable: false,
                            orderable: false,
                            render: function(_, _, data, row) {
                                return `
                                    <td class='text-center align-middle'>
                                        <button class="btn btn-primary btn-sm edit-record" data-key="${data.id}">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-record" data-key="${data.id}">
                                            <i class="mdi mdi-trash-can"></i> Delete
                                        </button>
                                    </td>
                                 `;
                            },
                        },
                    ]
                });

                $('#btnAddNewRecord').click(function(e) {
                    $('#recordModal').modal('toggle');
                });

                $(document).on('click', '#btn-close-modal', function() {
                    $("#btnSave").removeClass('d-none');
                    $("#btnSaveChanges").addClass('d-none');
                    $('.modal-title').text('Add New Record');
                    $.each(inputNames, function(index, value) {
                        $(`#${value}`).val('').removeClass("is-invalid");
                        $(`#${value}-error`).html("");
                    });
                });

                $('#btnSave').click(function() {
                    let data = $('#recordForm').serialize();
                    axios.post(`/faculty/store`, data).then((response) => {
                        if (response.status === 200) {
                            //swal({
                            //text: 'New record saved.',
                            //icon: 'success',
                            //timer: 2500,
                            //buttons: false,
                            //});
                            table.ajax.reload(null, false);
                            $('#btn-close-modal').trigger('click');
                        }
                    }).catch((error) => {
                        if (error.response.status === 422) {
                            let errors = error.response.data.errors;
                            $.each(inputNames, function(index, value) {
                                if (errors.hasOwnProperty(value)) {
                                    $(`#${value}`).addClass("is-invalid");
                                    $(`#${value}-error`).html("");
                                    $(`#${value}-error`).append(
                                        `${errors[value][0]}`
                                    );
                                } else {
                                    $(`#${value}`).removeClass("is-invalid");
                                    $(`#${value}-error`).html("");
                                }
                            });

                        }
                    });
                });

                $(document).on('click', '.edit-record', function(e) {
                    let id = $(this).attr('data-key');
                    $("#btnSave").addClass('d-none');
                    $("#btnSaveChanges").removeClass('d-none');
                    $("#btnSaveChanges").attr('data-key', id);
                    axios.get(`/faculty/edit/${id}`).then((response) => {
                        $('#recordModal').modal('toggle');
                        $('.modal-title').text('Edit Record');
                        $('#department_id').val(response.data.department_id);
                        $('#first_name').val(response.data.first_name);
                        $('#middle_name').val(response.data.middle_name);
                        $('#last_name').val(response.data.last_name);
                        $('#suffix').val(response.data.suffix);
                        $('#contact_no').val(response.data.contact_no);
                        $('#employment_status').val(response.data.employment_status);
                        $('#years_in_service').val(response.data.years_in_service);
                        $('#educational_qualification').val(response.data.educational_qualification);
                        $('#major').val(response.data.major);
                        $('#eligibility').val(response.data.eligibility);
                    })
                });

                $('#btnSaveChanges').click(function() {
                    let id = $(this).attr('data-key');
                    let data = $('#recordForm').serialize();
                    axios.put(`/faculty/${id}`, data).then((response) => {
                        if (response.status === 200) {
                            table.ajax.reload(null, false);
                            //swal({
                            //text: 'Changes been saved.',
                            //icon: 'success',
                            //timer: 2500,
                            //buttons: false,
                            //});
                            $('#btn-close-modal').trigger('click');
                        }
                    }).catch((error) => {
                        if (error.response.status === 422) {
                            let errors = error.response.data.errors;
                            $.each(inputNames, function(index, value) {
                                if (errors.hasOwnProperty(value)) {
                                    $(`#${value}`).addClass("is-invalid");
                                    $(`#${value}-error`).html("");
                                    $(`#${value}-error`).append(
                                        `${errors[value][0]}`
                                    );
                                } else {
                                    $(`#${value}`).removeClass("is-invalid");
                                    $(`#${value}-error`).html("");
                                }
                            });

                        }
                    });
                });

                $(document).on('click', '.delete-record', function(e) {
                    let id = $(this).attr('data-key');
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
                            axios.delete(`/faculty/${id}`).then((response) => {
                                if (response.status === 200) {
                                    //swal({
                                    //text: 'Record deleted.',
                                    //icon: 'success',
                                    //timer: 2500,
                                    //buttons: false,
                                    //});
                                    table.ajax.reload(null, false);
                                }
                            })
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
