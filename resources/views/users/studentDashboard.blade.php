@extends('layouts.app-student')

@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Student Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-uppercase">Student Subject Load</h2>
                        <hr>
                        <table width="100%" class="table table-condensed">
                            <tr>
                                <th class="text-center">Course No.</th>
                                <th class="text-center">Section</th>
                                <th class="text-center" width="20%">
                                    Descriptive Title</th>
                                <th class="text-center">Time</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Room</th>
                                <th class="text-center">Bldg</th>
                                <th class="text-center">Lec</th>
                                <th class="text-center">Lab</th>
                                <th class="text-center">Units</th>
                                <th class="text-center">Instructor</th>
                            </tr>
                            @php
                                $totalUnits = 0;
                                $totalHours = 0;
                            @endphp
                            @foreach ($schedules as $sched)
                                @php
                                    $start_time = Carbon\Carbon::parse($sched->start_time);
                                    $end_time = Carbon\Carbon::parse($sched->end_time);

                                    $time_diff = $end_time->diffInMinutes($start_time);
                                    $hours_decimal = $time_diff / 60;
                                    $hours_decimal_formatted = number_format($hours_decimal, 2);

                                    $totalUnits += $sched->subject_assignments->subject->units;
                                    $totalHours += $hours_decimal_formatted;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        {{ $sched->subject_assignments->subject->subject_code }}</td>
                                    <td class="text-center">
                                        {{ $student->fullcourse }}</td>
                                    <td class="text-start" width="20%">
                                        {{ $sched->subject_assignments->subject->description }}</td>
                                    <td class="text-start text-truncate">
                                        {{ $start_time->format('h:i a') . ' - ' . $end_time->format('h:i a') }}</td>
                                    <td class="text-center text-truncate">
                                        {{ $sched->week_days }}</td>
                                    <td class="text-center text-truncate">
                                        {{ $sched->room->room_name }}</td>
                                    <td class="text-center">
                                        &nbsp;</td>
                                    <td class="text-center">
                                        2.0</td>
                                    <td class="text-center">
                                        3.0</td>
                                    <td class="text-center">
                                        {{ $sched->subject_assignments->subject->units }}</td>
                                    <td class="text-start text-truncate">
                                        {{ $sched->subject_assignments->faculty->fullname }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <p>Note: This schedule may change depending on the no. of
                            students and faculty loading. Please be guided accordingly.</td>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
