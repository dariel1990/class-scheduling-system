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
                    <h3>Generate Class Schedules</h3>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="scheduleOption"
                                            id="wholeDepartment" value="DEPARTMENT">
                                        <label class="form-check-label" for="wholeDepartment">FOR THE WHOLE
                                            DEPARTMENT</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="scheduleOption"
                                            id="facultySchedule" value="FACULTY">
                                        <label class="form-check-label" for="facultySchedule">FOR A FACULTY</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="scheduleOption"
                                            id="classSchedule" value="CLASS">
                                        <label class="form-check-label" for="classSchedule">FOR A CLASS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 col-12 mt-0">
                                <div class="department d-none">
                                    <label for="con-mail">Department</label>
                                    <select class="form form-select" name="department" id="department">
                                        <option selected value="" disabled>-- Select Department -- </option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="faculty d-none">
                                    <label for="con-mail">Faculties</label>
                                    <select class="form form-select" name="faculty" id="faculty">
                                        <option selected value="" disabled>-- Select Faculty -- </option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->id }}">{{ $faculty->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="classes d-none">
                                    <label for="con-mail">Classes</label>
                                    <select class="form form-select" name="class" id="class">
                                        <option selected value="" disabled>-- Select a Class -- </option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-end mt-2 d-none generate-button">
                                    <button type="button" class="btn btn-primary" id="generate">
                                        <i class="fas fa-clock"></i> &nbsp; Generate Schedules
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <form id="save-schedules">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Instructor</th>
                                <th>Room</th>
                                <th>Schedule</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody id="schedules">
                        </tbody>
                    </table>
                    <span class="save-schedule"></span>
                </form>
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
        <script src="{{ asset('/assets/js/axios.min.js') }}"></script>
        <script>
            $(function() {
                $('#generate').click(function() {
                    var selectedOption = $('input[name="scheduleOption"]:checked').val();
                    var department = $('#department').val();
                    var faculty = $('#faculty').val();
                    var classId = $('#class').val();
                    let optionID = 0;

                    if (selectedOption == 'DEPARTMENT') {
                        optionID = department;
                    } else if (selectedOption == 'FACULTY') {
                        optionID = faculty;
                    } else if (selectedOption == 'CLASS') {
                        optionID = classId;
                    }

                    axios.get(`/genetic-algorithm/${selectedOption}/${optionID}`).then((response) => {
                        if (!response.data.schedule || response.data.schedule.length === 0) {
                            $('#schedules').append('<p>No schedules available.</p>');
                        } else {
                            $('#schedules').empty();
                            $('.save-schedule').empty();

                            response.data.schedule.forEach(schedule => {
                                console.log(schedule);
                                let row = $('<tr>');
                                row.append($('<td>').text(schedule.class.class.class_code)
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'academic_id[]', // Use [] to send as an array
                                        value: schedule.class.class.academic_id
                                    }))
                                );

                                row.append($('<td>').text(schedule.class.subject.subject_code)
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'sa_id[]', // Use [] to send as an array
                                        value: schedule.class.id
                                    }))
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'class_id[]', // Use [] to send as an array
                                        value: schedule.class.class.id
                                    })));
                                row.append($('<td>').text(schedule.class.faculty.fullname)
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'faculty_id[]', // Use [] to send as an array
                                        value: schedule.class.faculty_id
                                    })));
                                row.append($('<td>').text(schedule.room.room_name)
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'room_id[]', // Use [] to send as an array
                                        value: schedule.room.id
                                    })));
                                row.append($('<td>').text(
                                    schedule.time_slot.start_time + ' - ' +
                                    schedule.time_slot.end_time));
                                row.append($('<td>').text(schedule.time_slot.days)
                                    .append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'time_slot_id[]', // Use [] to send as an array
                                        value: schedule.time_slot.id
                                    })));
                                $('#schedules').append(row);
                            });

                            let submitBtn = $('<button>')
                                .text('Store Schedules')
                                .addClass('btn btn-primary')
                                .attr('type', 'submit')
                                .attr('id', 'btnSaveSchedules');

                            $(".save-schedule").append(submitBtn);
                        }
                    })
                });

                $('input[name="scheduleOption"]').change(function() {
                    var selectedValue = $('input[name="scheduleOption"]:checked').val();
                    if (selectedValue == 'DEPARTMENT') {
                        $('.department, .generate-button').removeClass('d-none').fadeIn();
                        $('.classes, .faculty').addClass('d-none').fadeOut();
                    } else if (selectedValue == 'FACULTY') {
                        $('.faculty, .generate-button').removeClass('d-none').fadeIn();
                        $('.classes, .department').addClass('d-none').fadeOut();
                    } else if (selectedValue == 'CLASS') {
                        $('.faculty, .department').addClass('d-none').fadeOut();
                        $('.classes, .generate-button').removeClass('d-none').fadeIn();
                    }
                });

                $('#save-schedules').submit(function() {
                    event.preventDefault(); // Prevent default form submission behavior

                    let data = $(this).serialize(); // Serialize form data
                    axios.post(`/classes-schedules/store/group`, data)
                        .then((response) => {
                            if (response.status == 200) {
                                swal({
                                    text: 'Schedules saved successfully.',
                                    icon: 'success',
                                    timer: 1500,
                                    buttons: false,
                                });
                                $('#schedules').empty();
                                $('.save-schedule').empty();
                            }
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                });

            });
        </script>
    @endpush
@endsection
