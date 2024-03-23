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
                            <h3 class="mt-2">Subjects</h3>
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
                                        <th width="20%" class="text-truncate">Subject Code</th>
                                        <th width="30%" class="text-truncate">Description</th>
                                        <th width="20%" class="text-truncate">Category</th>
                                        <th width="10%" class="text-truncate">No. of Units</th>
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
                        <div class="row g-2">
                            <div class="mb-2 col-md-12 mt-0">
                                <label>Subject Code</label>
                                {!! Form::text('subject_code', null, [
                                    'placeholder' => 'Input Subject Code',
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'subject_code',
                                ]) !!}
                                <div class='text-danger' id="subject_code-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label>Description</label>
                                {!! Form::text('description', null, [
                                    'placeholder' => 'Input Description',
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'description',
                                ]) !!}
                                <div class='text-danger' id="description-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Category</label>
                                <select class="form form-select" name="category" id="category">
                                    <option selected value="Major">Major</option>
                                    <option value="Minor">Minor</option>
                                </select>
                                <div class='text-danger' id="category-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label>No. of Units</label>
                                {!! Form::number('units', null, [
                                    'placeholder' => 'Input No. of Units',
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'units',
                                ]) !!}
                                <div class='text-danger' id="units-error"></div>
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
                    "subject_code",
                    "description",
                    "category",
                    "units",
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
                    ajax: `/subject/list`,
                    columns: [{
                            class: 'align-middle text-center',
                            data: 'subject_code',
                            name: 'subject_code',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle',
                            data: 'description',
                            name: 'description',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle',
                            data: 'category',
                            name: 'category',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'units',
                            name: 'units',
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
                    axios.post(`/subject/store`, data).then((response) => {
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
                    axios.get(`/subject/edit/${id}`).then((response) => {
                        $('#recordModal').modal('toggle');
                        $('.modal-title').text('Edit Record');
                        $('#subject_code').val(response.data.subject_code);
                        $('#description').val(response.data.description);
                        $('#category').val(response.data.category);
                        $('#units').val(response.data.units);
                    })
                });

                $('#btnSaveChanges').click(function() {
                    let id = $(this).attr('data-key');
                    let data = $('#recordForm').serialize();
                    axios.put(`/subject/${id}`, data).then((response) => {
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
                            axios.delete(`/subject/${id}`).then((response) => {
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
