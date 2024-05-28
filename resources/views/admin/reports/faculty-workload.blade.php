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
        th {
            border: 1px solid black;
            border-spacing: 0px;
        }

        th {
            font-size: 14px;
        }

        tbody td {
            border: 1px solid black;
            border-spacing: 0px;
            font-size: 16px;
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
                <h1 class="text-center text-uppercase" style="margin-top: 10px; margin-bottom: 0; letter-spacing: 1px;">
                    {{ $faculty->department->description }}
                </h1>
                <h2 class="text-center text-uppercase" style="margin-top: 0px; margin-bottom: 0; letter-spacing: 1px;">
                    FACULTY WORKLOAD
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
            <td class="text-start" style="border: none;" width="15%">Instructor Name</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">: {{ $faculty->fullname }}
            </td>
            <td class="text-start" style="border: none;" width="10%">&nbsp;</td>
            <td class="text-start text-truncate" style="border: none;" width="10%">Educ'l Qualification</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">:
                {{ $faculty->educational_qualification }}
            </td>
        </tr>
        <tr>
            <td class="text-start" style="border: none;" width="15%">Years in Service</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">:
                {{ $faculty->years_in_service }}
            </td>
            <td class="text-start" style="border: none;" width="10%">&nbsp;</td>
            <td class="text-start text-truncate" style="border: none;" width="10%">Major</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">:
                {{ $faculty->major }}
            </td>
        </tr>
        <tr>
            <td class="text-start" style="border: none;" width="15%">Status</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">:
                {{ $faculty->employment_status }}
            </td>
            <td class="text-start" style="border: none;" width="10%">&nbsp;</td>
            <td class="text-start text-truncate" style="border: none;" width="10%">Eligibility/PRC</td>
            <td class="text-start" style="border: none; font-weight: bold;" width="30%">:
                {{ $faculty->eligibility }}
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 50px;">
        <tr>
            <th>DAY</th>
            <th>TIME</th>
            <th>Subject Code</th>
            <th width="20%">Description</th>
            <th>Course Code</th>
            <th>No. of Students</th>
            <th>Units</th>
            <th>No. of Hours</th>
            <th>Room No.</th>
        </tr>
        @php
            $totalUnits = 0;
            $totalHours = 0;
        @endphp
        @foreach ($schedules as $sched)
            @php
                $start_time = Carbon\Carbon::parse($sched->time_slot->start_time);
                $end_time = Carbon\Carbon::parse($sched->time_slot->end_time);
                $days = $sched->time_slot->days;

                $time_diff = $end_time->diffInMinutes($start_time);
                $hours_decimal = $time_diff / 60;
                $hours_decimal_formatted = number_format($hours_decimal, 2);

                $totalUnits += $sched->subject_assignments->subject->units;
                $totalHours += $hours_decimal_formatted;
            @endphp
            <tr>
                <td class="text-center">{{ $days }}</td>
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
    <table width="100%" style="margin-top: 50px;">
        <tr>
            <td width="40%" style="border: none;">Prepared by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;">Conformed:</td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $faculty->department->program_head }}</strong><br>
                {{ $faculty->department->program_head_position }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td twidth="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $faculty->fullname }}</strong><br>
                {{ $faculty->employment_status == 'Contractual' ? 'Contractual' : 'Permanent' }} Instructor
            </td>
        </tr>
        <tr>
            <td width="40%" style="border: none; padding-top:50px;">Recommending Approval by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $settings['CAMPUS_DIRECTOR']->Keyvalue }}</strong><br>
                {{ $settings['CAMPUS_DIRECTOR_POSITION']->Keyvalue }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" class="text-center" style="border: none; padding-top:20px;">
                <strong>{{ $faculty->department->college_dean }}</strong><br>
                {{ $faculty->department->college_dean_position }}
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">Approved:</td>
        </tr>
        <tr>
            <td width="100%" class="text-center" style="border: none; padding-top:20px;">
                <strong>MARIA LADY SOL A. SUAZO, Ph.D.</strong><br>
                VP - Academic Affairs
            </td>
        </tr>
    </table>
</body>
