@extends('layouts.app-faculty')

@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Faculty Dashboard</h3>
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
                        <h2>FACULTY WORKLOAD</h2>
                        <hr>
                        <table width="100%" class="table table-condensed">
                            <tr>
                                <th class="text-center">DAY</th>
                                <th class="text-center">TIME</th>
                                <th class="text-center">Subject Code</th>
                                <th class="text-center" width="20%">Description</th>
                                <th class="text-center">Course Code</th>
                                <th class="text-center">No. of Students</th>
                                <th class="text-center">Units</th>
                                <th class="text-center">No. of Hours</th>
                                <th class="text-center">Room No.</th>
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
                                    <td class="text-center">{{ $sched->week_days }}</td>
                                    <td class="text-center text-truncate">
                                        {{ $start_time->format('h:i a') . ' - ' . $end_time->format('h:i a') }}</td>
                                    <td class="text-center">{{ $sched->subject_assignments->subject->subject_code }}</td>
                                    <td class="text-center text-truncate" width="20%">
                                        {{ $sched->subject_assignments->subject->description }}</td>
                                    <td class="text-center">{{ $sched->subject_assignments->class->class_code }}</td>
                                    <td class="text-center">{{ $sched->subject_assignments->student_population }}</td>
                                    <td class="text-center">{{ $sched->subject_assignments->subject->units }}</td>
                                    <td class="text-center">{{ $hours_decimal_formatted }}</td>
                                    <td class="text-center">{{ $sched->room->room_name }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="2">TOTAL UNITS</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center" width="20%">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">&nbsp;</td>
                                <td class="text-center">{{ $totalUnits }}</td>
                                <td class="text-center">{{ $totalHours }}</td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
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
