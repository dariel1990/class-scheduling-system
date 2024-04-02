<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial';
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        table,
        td,
        th {
            border-collapse: separate;
            border-spacing: 0px;
        }

        thead td,
        th {}

        th {
            font-size: 12px;
            padding: 1px 12px 1px 12px;
        }

        tbody td {
            font-size: 14px;
            padding: 1px 12px 1px 12px;
        }

        span {
            border: 1px solid black;
            padding: 1px 12px 1px 12px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <th class="text-end" style="border: none;" width="5%"></th>
            <th class="text-end" style="border: none;" width="15%"><img
                    src="{{ public_path('assets/images/NEMSU.png') }}" width="85%"></th>
            <th class="text-center" style="border: none;" width="60%">
                <h3 class="text-center" style="margin-top: 0; margin-bottom: 0; ">Republic of the Philippines</h3>
                <h2 class="text-center text-uppercase"
                    style="margin-top: 0; margin-bottom: 0; letter-spacing: 2px; color: #0d6efd;">
                    {{ $settings['SCHOOL_NAME']->Keyvalue }}</h2>
                <h3 class="text-center" style="font-weight: bold; margin-top: 0; margin-bottom: 0;">
                    {{ $settings['CAMPUS_NAME']->Keyvalue }}</h3>
                <h4 class="text-center" style="font-weight: normal; margin-top: 0; margin-bottom: 0;">
                    {{ $settings['CAMPUS_ADDRESS']->Keyvalue }}</h4>
                <h4 class="text-center" style="font-weight: normal; margin-top: 0; margin-bottom: 0;">Website: <code
                        style="color:blue">www.nemsu.edu.ph</code></h4>
            </th>
            <th class="text-start" style="border: none;" width="15%"><img
                    src="{{ public_path('assets/images/iso.png') }}" width="100%"></th>
            <th class="text-end" style="border: none;" width="5%"></th>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <th style="border: none;" colspan="6">
                <hr>
            </th>
        </tr>
        <tr>
            <th class="text-center" style="border: none; padding-top: 15px;" colspan="6">
                <h2 class="text-center text-uppercase" style="margin-top: 0px; margin-bottom: 0; letter-spacing: 1px;">
                    STUDENT SUBJECT LOAD
                </h2>
                <h2 class="text-center text-uppercase" style="margin-top: 0px; margin-bottom: 0; letter-spacing: 1px;">
                    {{ $defaultPeriod->semester == 1 ? 'FIRST' : 'SECOND' }} SEMESTER
                </h2>
                <h3 class="text-center text-uppercase" style="margin-top: 0px; margin-bottom: 0; letter-spacing: 1px;">
                    A.Y {{ $defaultPeriod->academic_year }}
                </h3>
            </th>
        </tr>
    </table>
    <table width="100%" style="margin-top: 15px;">
        <tr>
            <td class="text-center" style="border: none;" width="15%">Last name</td>
            <td class="text-center" style="border: none;" width="15%">First name</td>
            <td class="text-center" style="border: none;" width="15%">Middle Initial</td>
            <td class="text-center" style="border: none;" width="15%">Suffix</td>
        </tr>
        <tr>
            <td class="text-center" style="border: none; font-weight:bold" width="15%">
                {{ $student->last_name }}
            </td>
            <td class="text-center" style="border: none; font-weight:bold" width="15%">
                {{ $student->first_name }}
            </td>
            <td class="text-center" style="border: none; font-weight:bold" width="15%">
                {{ strtoupper(substr($student->last_name, 0, 1)) }}
            </td>
            <td class="text-center" style="border: none;" width="15%">{{ $student->suffix }}</td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 50px;">
        <tr>
            <th style="border-top:1px solid black; border-bottom: 1px solid black" class="text-truncate">Course No.</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Section</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black" class="text-start" width="20%">
                Descriptive Title</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Time</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Days</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Room</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Bldg</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Lec</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Lab</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Units</th>
            <th style="border-top:1px solid black; border-bottom: 1px solid black">Instructor</th>
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
                <td style="border-none;" class="text-center">
                    {{ $sched->subject_assignments->subject->subject_code }}</td>
                <td style="border-none;" class="text-start">
                    {{ $student->fullcourse }}</td>
                <td style="border-none;" class="text-start text-truncate" width="20%">
                    {{ $sched->subject_assignments->subject->description }}</td>
                <td style="border-none;" class="text-start text-truncate">
                    {{ $start_time->format('h:i a') . ' - ' . $end_time->format('h:i a') }}</td>
                <td style="border-none;" class="text-center text-truncate">
                    {{ $sched->week_days }}</td>
                <td style="border-none;" class="text-center text-truncate">
                    {{ $sched->room->room_name }}</td>
                <td style="border-none;" class="text-center">
                    &nbsp;</td>
                <td style="border-none;" class="text-center">
                    2.0</td>
                <td style="border-none;" class="text-center">
                    3.0</td>
                <td style="border-none;" class="text-center">
                    {{ $sched->subject_assignments->subject->units }}</td>
                <td style="border-none;" class="text-start text-truncate">
                    {{ $sched->subject_assignments->faculty->fullname }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center text-truncate"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center text-truncate" width="20%"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
            <td style="border-top:1px solid black" class="text-center"></td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 15px;">
        <tr>
            <td class="text-start" style="border: none;">
                Note: This schedule may change depending on the no. of
                students and faculty loading. Please be guided accordingly.</td>
        </tr>
    </table>
</body>
