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
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.css' rel='stylesheet' />
    <style>
        /* Custom CSS styles can be added here */
        .schedule-table th,
        .schedule-table td {
            text-align: center;
            vertical-align: middle;
            height: 100px;
            /* Adjust height as needed */
            border-top: 2px solid #000;
            /* Add top border to all cells */
            border-bottom: 2px solid #000;
            /* Add bottom border to all cells */
        }

        .time-cell {
            height: 50px;
            /* Adjust height as needed */
            border-top: none;
            /* Remove top border for time cells */
        }

        .half-hour {
            border-top: 2px solid #000;
            /* Add top border for half-hour cells */
        }

        .class-item {
            cursor: pointer;
            margin-bottom: 5px;
            background-color: #f0ad4e;
            border-radius: 5px;
            padding: 5px;
            color: #fff;
            width: 100%;
            /* Cover the whole cell */
        }
    </style>
@endprepend
@section('content')
    <h1 class="mb-4">Room View</h1>

    <div class="row">
        <!-- First column for room selection and class list -->
        <div class="col-md-3">
            <h2>Select Room</h2>
            <select id="room-select" class="form-select mb-3">
                <option value="">Select Room</option>
                <!-- Room options will be added here dynamically -->
                <option value="room1">Room 1</option>
                <option value="room2">Room 2</option>
                <!-- Add more room options as needed -->
            </select>

            <h2>Classes to Schedule</h2>
            <ul id="class-list" class="list-group">
                <!-- Classes will be added here dynamically -->
                <li class="list-group-item class-item" data-class="Class 1">Class 1</li>
                <li class="list-group-item class-item" data-class="Class 2">Class 2</li>
                <!-- Add more classes as needed -->
            </ul>
        </div>

        <!-- Second column for schedule table -->
        <div class="col-md-9">
            <h2>Room Schedule</h2>
            <table id="schedule-table" class="schedule-table table table-bordered table-primary">
                <thead>
                    <tr>
                        <th rowspan="2">Time</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <!-- Add more days as needed -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Schedule rows will be added here dynamically -->
                    <!-- Time slots will be generated dynamically -->
                </tbody>
            </table>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script>
            $(document).ready(function() {
                // Function to generate time slots
                function generateTimeSlots() {
                    var tbody = $('#schedule-table tbody');
                    tbody.empty();

                    for (var hour = 7; hour <= 21; hour++) { // 7 AM to 9 PM
                        var time = moment({
                            hour: hour
                        }).format('h:mm A'); // Format hour in 12-hour format with AM/PM
                        var tr = $('<tr>');
                        var timeCell = $('<td class="time-cell" rowspan="2">').text(time);
                        tr.append(timeCell);

                        // Add empty cells for each day
                        for (var day = 0; day < 5; day++) { // Monday to Friday
                            tr.append($('<td>'));
                        }
                        tbody.append(tr.clone());

                        // Add another row for the second 30-minute interval
                        tr = $('<tr>');
                        for (var day = 0; day < 5; day++) { // Monday to Friday
                            tr.append($('<td>'));
                        }
                        tbody.append(tr);
                    }
                }

                // Generate time slots when the page loads
                generateTimeSlots();

                // Make class items draggable and resizable
                $('.class-item').draggable({
                    revert: 'invalid', // Revert to original position if not dropped on a droppable target
                    zIndex: 100,
                    helper: 'clone' // Use a clone while dragging
                }).resizable({
                    handles: 'e, w', // Allow resizing from both sides
                    grid: [1000,
                    100], // Snap to 1-hour intervals horizontally and 30-minute intervals vertically
                    minWidth: 1000, // Set minimum width to 1000px (1 hour)
                    minHeight: 50, // Set minimum height to 50px (30 minutes)
                    stop: function(event, ui) {
                        // When resizing stops, adjust the width of the original element
                        $(this).width(ui.size.width);
                    }
                });

                // Make schedule table droppable
                $('#schedule-table td').droppable({
                    drop: function(event, ui) {
                        var droppedClass = ui.draggable.data('class'); // Get the dropped class
                        var time = $(this).siblings().first()
                    .text(); // Get the time from the first cell of the row
                        var day = $(this).index() - 1; // Get the day index (0-based)
                        var dayName = $(this).closest('tr').prev().find('th:eq(' + (day + 1) + ')')
                    .text(); // Get the day name
                        var eventDetails = droppedClass + ' - ' + time + ' (' + dayName +
                        ')'; // Construct event details
                        $(this).append($('<div class="event class-item">').text(
                        eventDetails)); // Append event to the cell
                    }
                });
            });
        </script>
    @endpush
@endsection
