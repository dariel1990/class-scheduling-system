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
                            <h3 class="mt-2">Users</h3>
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
                                        <th width="40%" class="text-truncate">Fullname</th>
                                        <th width="20%" class="text-truncate">Username</th>
                                        <th width="20%" class="text-truncate">Role</th>
                                        <th width="20%" class="text-center">Action</th>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
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
                        <h5 class="text-primary">User Credentials</h5>
                        <hr class="mt-0 mb-2">
                        <div class="mb-2 m-form__group">
                            <div class="input-group">
                                <span class="input-group-text" style="width:140px">Username</span>
                                <input class="form-control" name="username" id="username" type="text"
                                    placeholder="Enter Username">
                            </div>
                            <small class="text-danger" id="username-error"></small>
                        </div>
                        <div class="mb-2 m-form__group">
                            <div class="input-group"><span class="input-group-text" style="width:140px">Email Address</span>
                                <input class="form-control" name="email" id="email" type="text"
                                    placeholder="Enter Email">
                            </div>
                            <small class="text-danger" id="email-error"></small>
                        </div>
                        <div class="mb-2 m-form__group">
                            <div class="input-group"><span class="input-group-text" style="width:140px">Password</span>
                                <input class="form-control" name="password" id="password" type="password"
                                    placeholder="Enter Password">
                            </div>
                            <small class="text-danger" id="password-error"></small>
                        </div>
                        <div class="mb-2 m-form__group">
                            <div class="input-group"><span class="input-group-text" style="width:140px">Role</span>
                                <select class="form-select" id="roles" name="roles[]">
                                    <option disabled selected value="0">Select One</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger" id="roles-error"></small>
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
        <!-- Responsive examples -->
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('/assets/libs/sweetalert2/sweetalert.min.js') }}"></script>
        <script src="{{ asset('/assets/js/axios.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                const inputNames = [
                    "username",
                    "email",
                    "password",
                    "roles",
                ];

                let table = $('#dataTable').DataTable({
                    serverSide: true,
                    processing: true,
                    destroy: true,
                    ordering: false,
                    info: true,
                    language: {
                        processing: '<i class="text-primary fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                    },
                    ajax: `/users-list`,
                    columns: [{
                            class: 'align-middle text-start',
                            data: 'fullname',
                            name: 'fullname',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'username',
                            name: 'username',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle',
                            data: 'roles',
                            name: 'roles',
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
                    $('#password').attr('disabled', false);
                    $.each(inputNames, function(index, value) {
                        $(`#${value}`).val('').removeClass("is-invalid");
                        $(`#${value}-error`).removeAttr('style').html("");
                    });
                });

                $('#btnSave').click(function() {
                    let data = $('#recordForm').serialize();
                    axios.post(`/users`, data).then((response) => {
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
                                    $(`#${value}-error`).attr('style', 'margin-left: 140px')
                                        .html("");
                                    $(`#${value}-error`).append(
                                        `${errors[value][0]}`
                                    );
                                } else {
                                    $(`#${value}`).removeClass(
                                        "is-invalid");
                                    $(`#${value}-error`).removeAttr('style').html("");
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
                    axios.get(`/users/${id}/edit`).then((response) => {
                        $('#recordModal').modal('toggle');
                        $('.modal-title').text('Edit Record');
                        $('#password').attr('disabled', true);
                        $('#username').val(response.data.username);
                        $('#email').val(response.data.email);
                        $('#roles').val(response.data.roles[0].id);
                    })
                });

                $('#btnSaveChanges').click(function() {
                    let id = $(this).attr('data-key');
                    let data = $('#recordForm').serialize();
                    axios.put(`/users/${id}`, data).then((response) => {
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
                            axios.delete(`/users/${id}`).then((response) => {
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
