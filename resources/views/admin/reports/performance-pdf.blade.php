<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Montserrat';
        }
        .text-center {
            text-align : center;
        }

        .text-end {
            text-align : right;
        }

        .text-start {
            text-align : left;
        }

        .font-weight-bold {
            font-weight : bold;
        }
        table, td, th {
            border-collapse: separate;
            border-spacing: 0px;
        }
        thead  td, th {
            border: 1px solid black;
            border-spacing: 0px;
        }
        th {
            font-size: 12px;
        }
        tbody  td {
            border: 1px solid black;
            border-spacing: 0px;
            font-size: 16px;
        }
        span {
            border: 1px solid black;
            padding: 1px 12px 1px 12px;
        }

        .fw-bold{
            font-weight: bold;
        }

        .text-uppercase{
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
            <th class="text-end" style="border: none;" width="15%"><img src="file:///laragon/www/facultyEvaluation/public/assets/images/NEMSU.png" width="85%"></th>
            <th class="text-center" style="border: none;" width="60%">
                <h3 class="text-center" style="margin-top: 0; margin-bottom: 0; ">Republic of the Philippines</h3>
                <h2 class="text-center text-uppercase" style="margin-top: 0; margin-bottom: 0; letter-spacing: 2px; color: #0d6efd;">{{ $settings['SCHOOL_NAME']->Keyvalue }}</h1>
                <h3 class="text-center" style="font-weight: bold; margin-top: 0; margin-bottom: 0;">{{ $settings['CAMPUS_NAME']->Keyvalue }}</h3>
                <h4 class="text-center" style="font-weight: normal; margin-top: 0; margin-bottom: 0;">{{ $settings['CAMPUS_ADDRESS']->Keyvalue }}</h4>
                <h4 class="text-center" style="font-weight: normal; margin-top: 0; margin-bottom: 0;">Website: <code style="color:blue">www.nemsu.edu.ph</code></h4>
            </td>
            <th class="text-start" style="border: none;" width="15%"><img src="file:///laragon/www/facultyEvaluation/public/assets/images/iso.png" width="100%"></th>
            <th class="text-end" style="border: none;" width="5%"></th>
        </tr>
        <tr>
            <th style="border: none;" colspan="6"><hr></th>
        </tr>
        <tr>
            <th class="text-center" style="border: none; padding-top: 15px;" colspan="6">
                <h2 class="text-center text-uppercase" style="margin-top: 10px; margin-bottom: 0; letter-spacing: 1px;">
                    {{ $evaluationType }} PERFORMANCE EVALUATION FOR FACULTY
                </h2>
            </th>
        </tr>
    </table>
    <table width="100%" style="margin-top: 15px;">
        <tr>
            <td class="text-start text-truncate" style="border: none;" width="10%">Name of Instructor</td>
            <td class="text-start" style="border: none; font-weight: bold;">: {{ $evaluation->faculty->fullname }}</td>
        </tr>
        <tr>
            <td class="text-start text-truncate" style="border: none;" width="10%">For the Cycle Period: </td>
            <td class="text-start" style="border: none; font-weight: bold;">: A.Y. {{ $evaluation->academicYear->academic_year}}</td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 25px;">
        <tr>
            <th rowspan="3" style="padding: 10px 12px;">YEAR</th>
            <th rowspan="3" style="padding: 10px 12px;">SEMESTER</th>
            <th colspan="4" style="padding: 10px 12px;" rowspan="1" class="text-center">CRITERIA</th>
            <th rowspan="3" style="padding: 10px 12px;">TOTAL</th>
        </tr>
        <tr>
            @foreach($criterias as $criteria)
                <th style="vertical-align: top; border-bottom: none; padding: 5px 2px">{{ $criteria->criteria}}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($criterias as $criteria)
                <th style="border-top: none; padding: 5px 2px">({{ $criteria->percentage }}%)</th>
            @endforeach
        </tr>
        <tr>
            <td class="text-center text-truncate">{{ $evaluation->academicYear->academic_year}}</td>
            <td class="text-center text-truncate">{{ $evaluation->academicYear->semester == '1' ? 'FIRST' : 'SECOND' }}</td>
            @foreach($criterias as $criteria)
                <td class="text-center" style="padding: 25px 0; vertical-align: middle;">{{ number_format($calculateRating[$criteria->id], 2)}}</td>
            @endforeach
            <td class="text-center" style="padding: 25px 0; vertical-align: middle;"><strong>{{ number_format($totalSum, 2) }}</strong></td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 50px;">
        <tr>
            <td width="40%" style="border: none;">Prepared by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;"></td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:50px;">
                <strong>{{ $settings['HR']->Keyvalue }}</strong><br>
                {{ $settings['HR_POSITION']->Keyvalue }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;"></td>
        </tr>
        <tr>
            <td width="40%" style="border: none; padding-top:50px;">Noted by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:50px;">
                <strong>{{ $settings['ASSISTANT_CAMPUS_DIRECTOR']->Keyvalue }}</strong><br>
                {{ $settings['ASSISTANT_CAMPUS_DIRECTOR_POSITION']->Keyvalue }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none; padding-top:50px;" class="text-center">
                <strong>{{ $settings['DFIMES_CHAIRMAN']->Keyvalue }}</strong><br>
                {{ $settings['DFIMES_CHAIRMAN_POSITION']->Keyvalue }}
            </td>
        </tr>
        <tr>
            <td width="40%" style="border: none; padding-top:50px;">Approved by:</td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;"></td>
        </tr>
        <tr>
            <td width="40%" class="text-center" style="border: none; padding-top:50px;">
                <strong>{{ $settings['CAMPUS_DIRECTOR']->Keyvalue }}</strong><br>
                {{ $settings['CAMPUS_DIRECTOR_POSITION']->Keyvalue }}
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="border: none;"></td>
        </tr>
    </table>
</div>
</body>

