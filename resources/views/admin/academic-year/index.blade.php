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
                            <h3 class="mt-2">Academic Year</h3>
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
                                        <th class="text-center text-truncate">Academic Year</th>
                                        <th class="text-center">Semester</th>
                                        <th class="text-center">Default</th>
                                        <th class="text-center">Action</th>
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
    <div class="modal fade" id="academicYearModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                    <form id="academicYearForm">
                        <div class="row mb-0 py-0">
                            <div class="col-12 form-group mb-3">
                                <label class="text-uppercase h5 font-weight-medium">Academic Year</label>
                                <input class="form-control form-control-lg" name="academic_year" id="academic_year"
                                    type="text" placeholder="Input Academic Year">
                                <span class="text-danger" id="academic_year-error"></span>
                            </div>
                            <div class="col-12 form-group mb-3">
                                <label class="text-uppercase h5 font-weight-medium">Semester</label>
                                <input type="text" class="form-control form-control-lg" id="semester" name="semester"
                                    placeholder="Input Semester">
                                <span class="text-danger" id="semester-error"></span>
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
            window.updateDefaultStatus = function(id, isChecked) {
                axios.put(`/academic-year-updateDefaultStatus/${id}`).then((response) => {
                    if (response.status === 200) {
                        location.reload();
                    }
                })
            };

            $(document).ready(function() {
                const inputNames = [
                    "academic_year",
                    "semester",
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
                    ajax: `/academic/year/list`,
                    columns: [{
                            class: 'align-middle text-center',
                            data: 'academic_year',
                            name: 'academic_year',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'semester',
                            name: 'semester',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle',
                            data: 'isDefault',
                            name: 'isDefault',
                            render: function(_, _, data, row) {
                                let isChecked = data.isDefault ? 'checked disabled' : '';
                                let checkBoxHtml =
                                    `<input type="checkbox" class="form-check-input input-checkbox" ${isChecked} data-key="${data.id}">`;
                                return `
                                    <div class="form-check form-switch form-switch-md d-flex justify-content-center align-items-center">
                                        ${checkBoxHtml}
                                    </div>
                                `;
                            },
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
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm edit-record" data-key="${data.id}">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-record" data-key="${data.id}">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                 `;
                            },
                        },
                    ]
                });

                $(document).on('change', '.input-checkbox', function() {
                    let id = $(this).data('key');
                    let isChecked = $(this).prop('checked');

                    axios.put(`/academic-year-updateDefaultStatus/${id}`).then((response) => {
                        if (response.status === 200) {
                            swal({
                                text: 'Default year changed.',
                                icon: 'success',
                                timer: 1500,
                                buttons: false,
                            });
                            table.ajax.reload(null, false);
                        }
                    })
                });

                $('#btnAddNewRecord').click(function(e) {
                    $('#academicYearModal').modal('toggle');
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
                    let data = $('#academicYearForm').serialize();
                    axios.post(`/academic/year/store`, data).then((response) => {
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
                    axios.get(`/academic/year/edit/${id}`).then((response) => {
                        $('#academicYearModal').modal('toggle');
                        $('.modal-title').text('Edit Record');
                        $('#academic_year').val(response.data.academic_year);
                        $('#semester').val(response.data.semester);
                    })
                });

                $('#btnSaveChanges').click(function() {
                    let id = $(this).attr('data-key');
                    let data = $('#academicYearForm').serialize();
                    axios.put(`/academic/year/${id}`, data).then((response) => {
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
                            axios.delete(`/academic/year/${id}`).then((response) => {
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
