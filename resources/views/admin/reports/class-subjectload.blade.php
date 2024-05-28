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
            padding: 1px 12px;
        }

        tbody td {
            font-size: 14px;
            padding: 5px 12px;
        }

        span {
            border: 1px solid black;
            padding: 1px 12px;
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
                    CLASS PROGRAM
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
    <table style="margin-top: 15px;">
        <tr>
            <td class="text-center" style="border: none;" width="15%">Course/Year/Section: </td>
            <th class="text-center" style="border: none;" width="15%"> {{ $classes->class_code }}</th>
        </tr>
    </table>
    <table width="100%" style="margin-top: 50px; border-collapse: collapse">
        <tr>
            <th style="border: 1px solid black">Time</th>
            <th style="border: 1px solid black">Course No.</th>
            <th style="border: 1px solid black" class="text-center" width="20%">
                Descriptive Title</th>
            <th style="border: 1px solid black">No. of Units</th>
            <th style="border: 1px solid black">No. of Hours</th>
            <th style="border: 1px solid black">Instructor</th>
            <th style="border: 1px solid black">Room</th>
        </tr>
        @php
            $totalUnits = 0;
            $totalHours = 0;
        @endphp

        @foreach ($groupedSchedules as $days => $times)
            @foreach (['AM', 'PM'] as $timeOfDay)
                @if (!empty($times[$timeOfDay]))
                    <tr>
                        <td style="font-weight: bold; border: 1px solid black" class="text-center">
                            {{ $timeOfDay }}
                        </td>
                        <td colspan="6" style="font-weight: bold; border: 1px solid black" class="text-center">
                            {{ $timeOfDay == 'PM' ? 'Lunch Break' : $days }}
                        </td>
                    </tr>
                    @foreach ($times[$timeOfDay] as $sched)
                        @php
                            $start_time = \Carbon\Carbon::parse($sched->time_slot->start_time);
                            $end_time = \Carbon\Carbon::parse($sched->time_slot->end_time);
                            $time_diff = $end_time->diffInMinutes($start_time);
                            $hours_decimal = $time_diff / 60;
                            $daysArray = explode('-', $days);
                            $numDays = count($daysArray);
                            $hours_decimal_formatted = number_format($hours_decimal * $numDays, 2);

                            $totalUnits += $sched->subject_assignments->subject->units;
                            $totalHours += $hours_decimal_formatted;
                        @endphp
                        <tr>
                            <td style="border: 1px solid black" class="text-center text-truncate">
                                {{ $start_time->format('h:i a') . ' - ' . $end_time->format('h:i a') }}</td>
                            <td style="border: 1px solid black" class="text-center">
                                {{ $sched->subject_assignments->subject->subject_code }}</td>
                            <td width="30%" style="border: 1px solid black" class="text-start">
                                {{ $sched->subject_assignments->subject->description }}</td>
                            <td style="border: 1px solid black" class="text-center">
                                {{ $sched->subject_assignments->subject->units }}</td>
                            <td style="border: 1px solid black" class="text-center">{{ $hours_decimal_formatted }}</td>
                            <td style="border: 1px solid black" class="text-start text-truncate">
                                {{ $sched->subject_assignments->faculty->fullname }}
                            </td>
                            <td style="border: 1px solid black" class="text-truncate">{{ $sched->room->room_name }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endforeach

        <tr>
            <td colspan="3" style="border:1px solid black" class="text-center">Total Number of Units</td>
            <td style="border:1px solid black" class="text-center fw-bold">{{ $totalUnits }}</td>
            <td style="border:1px solid black" class="text-center fw-bold">{{ $totalHours }}</td>
            <td style="border:1px solid black"></td>
            <td style="border:1px solid black"></td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 50px;">
        <tr>
            <td width="40%" style="border: none;">Prepared by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;">Recommending Approval:</td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $classes->department->program_head }}</strong><br>
                {{ $classes->department->program_head_position }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td twidth="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $settings['REGISTRAR']->Keyvalue }}</strong><br>
                {{ $settings['REGISTRAR_POSITION']->Keyvalue }}
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">Noted By:</td>
        </tr>
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $classes->department->college_dean }}</strong><br>
                {{ $classes->department->college_dean_position }}
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">Approved:</td>
        </tr>
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $settings['CAMPUS_DIRECTOR']->Keyvalue }}</strong><br>
                {{ $settings['CAMPUS_DIRECTOR_POSITION']->Keyvalue }}
            </td>
        </tr>
    </table>
</body>
