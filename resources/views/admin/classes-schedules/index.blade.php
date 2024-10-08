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
                            <h3 class="mt-2">Class Schedules</h3>
                        </div>
                        <div class="col-6 text-end">
                            <button type="button" class="btn btn-success text-uppercase" id="btnGeneticAlgo">
                                Group Scheduling
                            </button>
                            <button type="button" class="btn btn-primary text-uppercase" id="btnAddNewRecord">
                                Create Schedule Manually
                            </button>
                            <button type="button" class="btn btn-info text-uppercase" id="btnPrintSchedules">
                                <i class="fa fa-print"></i> Print Schedules
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
                                        <th width="10%" class="text-truncate">Class</th>
                                        <th width="10%" class="text-truncate">Subject</th>
                                        <th width="20%" class="text-truncate">Assigned Faculty</th>
                                        <th width="10%" class="text-truncate">Room</th>
                                        <th width="20%" class="text-truncate">Schedule</th>
                                        <th width="10%" class="text-truncate">Week Days</th>
                                        <th width="30%" class="text-center">Action</th>
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
                                <input type="hidden" name="academic_id" value="{{ $defaultAY->id }}">
                                <label for="con-mail">Class</label>
                                <select class="form form-select" name="class_id" id="class_id">
                                    <option selected value="" disabled>-- Select Class -- </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_code }}</option>
                                    @endforeach
                                </select>
                                <div class='text-danger' id="class_id-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Subject</label>
                                <select class="form form-select" name="sa_id" id="sa_id">
                                    <option selected value="" disabled>-- Select Subject -- </option>

                                </select>
                                <div class='text-danger' id="sa_id-error"></div>
                            </div>
                            <div class="mb-2 col-md-8 mt-0">
                                <input type="hidden" name="faculty_id" id="faculty_id">
                                <label for="con-mail">Assigned Faculty</label>
                                {!! Form::text('faculty', null, [
                                    'class' => 'form-control',
                                    'id' => 'faculty',
                                    'autocomplete' => 'off',
                                    'readonly' => 'true',
                                ]) !!}
                                <div class='text-danger' id="faculty_id-error"></div>
                            </div>
                            <div class="mb-2 col-md-4 mt-0">
                                <label for="con-mail">Enrolled Students</label>
                                {!! Form::text('student_population', null, [
                                    'class' => 'form-control',
                                    'id' => 'student_population',
                                    'autocomplete' => 'off',
                                    'readonly' => 'true',
                                ]) !!}
                                <div class='text-danger' id="student_population-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Room</label>
                                <select class="form form-select" name="room_id" id="room_id">
                                    <option selected value="" disabled>-- Select Room -- </option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->room_name }} - {{ $room->room_type }}
                                            (Cap: {{ $room->capacity }} students)
                                        </option>
                                    @endforeach
                                </select>
                                <div class='text-danger' id="room_id-error"></div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Time Slots</label>
                                <select class="form form-select" name="time_slot_id" id="time_slot_id">
                                    <option selected value="" disabled>-- Select a Time Slot -- </option>
                                    @foreach ($timeSlots->groupBy('days') as $day => $slots)
                                        <optgroup label="{{ $day }}">
                                            @foreach ($slots as $timeSlot)
                                                <option value="{{ $timeSlot->id }}">
                                                    {{ Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} -
                                                    {{ Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class='text-danger' id="time_slot_id-error"></div>
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
    <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark ">
                    <span class="modal-title h4 text-uppercase text-white">
                        <i class="fa fa-print"></i> Print Schedules
                    </span>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="printScheduleForm">
                        @csrf
                        <div class="row g-2">
                            <label class="d-block">Print Schedule for</label>
                            <div class="mb-2 col-md-12 mt-0 ms-5">
                                <input type="hidden" id="academic_id" value="{{ $defaultAY->id }}">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="schedule_type"
                                            id="facultySchedule" value="FACULTY">
                                        <label class="form-check-label" for="facultySchedule">FACULTY WORK LOAD</label>
                                    </div><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="schedule_type"
                                            id="studentSchedule" value="STUDENT">
                                        <label class="form-check-label" for="studentSchedule">STUDENT SUBJECT LOAD</label>
                                    </div><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="schedule_type"
                                            id="classSchedule" value="CLASS">
                                        <label class="form-check-label" for="classSchedule">CLASS LOAD</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Faculties</label>
                                <select class="form form-select" id="facultyLoad" disabled>
                                    <option selected value="" disabled>-- Select Faculty -- </option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}">{{ $faculty->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Students</label>
                                <select class="form form-select" id="studentLoad" disabled>
                                    <option selected value="" disabled>-- Select Student -- </option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-12 mt-0">
                                <label for="con-mail">Classes</label>
                                <select class="form form-select" id="classLoad" disabled>
                                    <option selected value="" disabled>-- Select Class -- </option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <button class="btn btn-primary w-100 btn-block" id="btnPrint">
                            <i class="fa fa-file-pdf"></i> Print Preview
                        </button>
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
                function isMobileOrTablet() {
                    return window.innerWidth <= 768; // Adjust the threshold as needed
                }

                // Set initial values
                let topPosition = 10;
                let leftPosition = 265;

                // Check if it's a mobile or tablet and update positions
                if (isMobileOrTablet()) {
                    topPosition = 0;
                    leftPosition = 0;
                }

                if (isMobileOrTablet()) {
                    topPosition = 0;
                    leftPosition = 0;
                }

                let isEdit = false;

                $('#facultyLoad').select2({
                    width: '100%',
                    dropdownParent: $('#printModal')
                });

                $('#studentLoad').select2({
                    width: '100%',
                    dropdownParent: $('#printModal')
                });

                $('#classLoad').select2({
                    width: '100%',
                    dropdownParent: $('#printModal')
                });

                $('#class_id').select2({
                    width: '100%',
                    dropdownParent: $('#recordModal')
                });

                $('#sa_id').select2({
                    width: '100%',
                    dropdownParent: $('#recordModal')
                });

                $('#time_slot_id').select2({
                    width: '100%',
                    dropdownParent: $('#recordModal')
                });

                const inputNames = [
                    "class_id",
                    "faculty_id",
                    "student_population",
                    "room_id",
                    "start_time",
                    "end_time",
                    "week_day",
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
                    ajax: `/classes-schedules/list`,
                    columns: [{
                            class: 'align-middle text-center',
                            data: 'class',
                            name: 'class',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'subject',
                            name: 'subject',
                            searchable: true,
                            orderable: false,
                        },
                        {
                            class: 'align-middle',
                            data: 'faculty',
                            name: 'faculty',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'room',
                            name: 'room',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'time_slot',
                            name: 'time_slot',
                            searchable: true,
                            orderable: false
                        },
                        {
                            class: 'align-middle text-center',
                            data: 'week_days',
                            name: 'week_days',
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
                                        <button class="btn btn-primary btn-sm edit-record" data-key="${data.sa_id}">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-record" data-key="${data.sa_id}">
                                            <i class="mdi mdi-trash-can"></i> Delete
                                        </button>
                                    </td>
                                 `;
                            },
                        },
                    ]
                });

                $('#class_id').change(function() {
                    let classID = $(this).val();
                    let route = '';
                    if (isEdit) {
                        route = `/api/subject-assignment-all/${classID}`;
                    } else {
                        route = `/api/subject-assignment-no-cs/${classID}`;
                    }

                    axios.get(route).then((response) => {
                        let records = response.data;
                        let $select = $('#sa_id');
                        // Clear existing options
                        $select.empty();
                        $select.append($('<option>', {
                            value: '',
                            disabled: true,
                            selected: true,
                            text: '-- Select Subjects --'
                        }));

                        $.each(records, function(index, sa) {
                            $select.append($('<option>', {
                                value: sa.id,
                                text: sa.subject.subject_code + ' - ' + sa.subject
                                    .description
                            }));
                        });

                    })
                });

                $('#sa_id').change(function() {
                    let saID = $(this).val();
                    axios.get(`/api/subject-assigned-faculty/${saID}`).then((response) => {
                        let records = response.data;
                        $('#faculty_id').val(records.faculty_id);
                        $('#faculty').val(records.faculty.fullname);
                        $('#student_population').val(records.student_population);
                        console.log(saID, records.faculty_id);
                        axios.get(`/smart-suggestion/${saID}/${records.faculty_id}`).then((
                            response) => {
                            if (response.status == 200) {
                                let data = response.data;
                                $('#room_id').val(data.schedule[0].room.id);
                                $('#time_slot_id').val(data.schedule[0].time_slot.id).trigger(
                                    'change');
                            }
                        })
                    })
                });

                $('#btnAddNewRecord').click(function(e) {
                    $('#recordModal').modal('toggle');
                });

                $('#btnPrintSchedules').click(function(e) {
                    $('#printModal').modal('toggle');
                });

                $(document).on('click', '#btn-close-modal', function() {
                    isEdit = false;
                    $("#btnSave").removeClass('d-none');
                    $("#btnSaveChanges").addClass('d-none');
                    $('.modal-title').text('Add New Record');
                    $(`#sa_id`).empty().attr('disabled', false);
                    $('#faculty').val('');
                    $(`#class_id`).val('').trigger('change').attr('disabled', false);
                    $('#week_day').val([]).trigger('change');
                    $.each(inputNames, function(index, value) {
                        $(`#${value}`).val('').removeClass("is-invalid");
                        $(`#${value}-error`).html("");
                    });
                });

                $('#btnSave').click(function() {
                    let data = $('#recordForm').serialize();
                    axios.post(`/classes-schedules/store`, data).then((response) => {
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

                $('#btnGeneticAlgo').click(function() {
                    box = new WinBox(`Generate using Genetic Algorithm `, {
                        root: document.querySelector('.page-content'),
                        class: ["no-min", "no-full", "no-move", "no-max"],
                        url: `/classes-schedules/genetic-algorithm/?winbox=1`,
                        index: 999999,
                        background: "#2a3042",
                        border: "0.3em",
                        width: "100%",
                        height: "95%",
                        top: topPosition,
                        left: leftPosition,
                        right: 10,
                        onclose: function(force) {
                            table.ajax.reload(null, false);
                        }
                    });
                });

                $(document).on('click', '.edit-record', function(e) {
                    isEdit = true;
                    let id = $(this).attr('data-key');
                    $("#btnSave").addClass('d-none');
                    $("#btnSaveChanges").removeClass('d-none');
                    $("#btnSaveChanges").attr('data-key', id);
                    axios.get(`/classes-schedules/edit/${id}`).then((response) => {
                        let classID = response.data[0].subject_assignments.class_id;
                        let saID = response.data[0].sa_id;
                        $('#recordModal').modal('toggle');
                        $('.modal-title').text('Edit Record');
                        $('#class_id').val(classID).trigger('change');
                        setTimeout(() => {
                            $('#sa_id').val(saID).trigger('change');
                        }, 500);
                        $('#room_id').val(response.data[0].room_id);
                        $('#time_slot_id').val(response.data[0].time_slot_id);

                        // Refresh Select2
                        $('#time_slot_id').trigger('change');
                        $('#class_id').attr('disabled', true);
                        $('#sa_id').attr('disabled', true);
                    })
                });

                $('#btnSaveChanges').click(function() {
                    let id = $(this).attr('data-key');
                    let data = $('#recordForm').serialize();
                    axios.put(`/classes-schedules/${id}`, data).then((response) => {
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
                            axios.delete(`/classes-schedules/${id}`).then((response) => {
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

                $('input[type=radio][name=schedule_type]').change(function() {
                    if (this.value === 'FACULTY') {
                        $('#facultyLoad').prop('disabled', false).show();
                        $('#studentLoad').val('').trigger('change').prop('disabled', true).hide();
                        $('#classLoad').val('').trigger('change').prop('disabled', true).hide();
                    } else if (this.value === 'STUDENT') {
                        $('#studentLoad').prop('disabled', false).show();
                        $('#facultyLoad').val('').trigger('change').prop('disabled', true).hide();
                        $('#classLoad').val('').trigger('change').prop('disabled', true).hide();
                    } else if (this.value === 'CLASS') {
                        $('#classLoad').prop('disabled', false).show();
                        $('#facultyLoad').val('').trigger('change').prop('disabled', true).hide();
                        $('#studentLoad').val('').trigger('change').prop('disabled', true).hide();
                    }
                });

                $('#btnPrint').click(function() {
                    var scheduleType = $('input[name="schedule_type"]:checked').val();
                    var academicId = $('#academic_id').val();

                    var selectedId;
                    if (scheduleType === 'FACULTY') {
                        selectedId = $('#facultyLoad').val();

                        box = new WinBox(`FACULTY WORKLOAD`, {
                            root: document.querySelector('.page-content'),
                            class: ["no-min", "no-full", "no-resize", "no-move"],
                            url: `/reports/faculty-workload/${selectedId}/${academicId}`,
                            index: 999999,
                            width: window.innerWidth,
                            height: window.innerHeight,
                            background: "#2a3042",
                            x: "center",
                            y: 0
                        });
                    } else if (scheduleType === 'STUDENT') {
                        selectedId = $('#studentLoad').val();

                        box = new WinBox(`STUDENT SUBJECT LOAD`, {
                            root: document.querySelector('.page-content'),
                            class: ["no-min", "no-full", "no-resize", "no-move"],
                            url: `/reports/student-subjectload/${selectedId}/${academicId}`,
                            index: 999999,
                            width: window.innerWidth,
                            height: window.innerHeight,
                            background: "#2a3042",
                            x: "center",
                            y: 0
                        });
                    } else if (scheduleType === 'CLASS') {
                        selectedId = $('#classLoad').val();

                        box = new WinBox(`CLASS SUBJECT LOAD`, {
                            root: document.querySelector('.page-content'),
                            class: ["no-min", "no-full", "no-resize", "no-move"],
                            url: `/reports/class-subjectload/${selectedId}/${academicId}`,
                            index: 999999,
                            width: window.innerWidth,
                            height: window.innerHeight,
                            background: "#2a3042",
                            x: "center",
                            y: 0
                        });
                    }

                });
            });
        </script>
    @endpush
@endsection
