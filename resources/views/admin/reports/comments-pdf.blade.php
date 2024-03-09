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
                <h2 class="text-center text-uppercase" style="margin-top: 10px; margin-bottom: 0; letter-spacing: 1px;">{{ $evaluationType }}'S REMARKS / COMMENTS</h2>
                <h3 class="text-center" style="margin-top: 0; margin-bottom: 0; font-weight: normal;">
                    Rating Period - {{ $evaluation->academicYear->semester == '1' ? '1st' : '2nd' }} Sem, AY {{ $evaluation->academicYear->academic_year}}
                </h3>
            </th>
        </tr>
    </table>
    <table width="100%" style="margin-top: 15px;">
        <tr>
            <td class="text-start text-truncate" style="border: none;" width="10%">FACULTY</td>
            <td class="text-start" style="border: none; font-weight: bold;">: {{ $evaluation->faculty->fullname }}</td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 15px;">
        @foreach ($comments as $comment)
            <tr>
                <td class="fw-normal text-center" width="5%" style="padding: 5px 2px">{{ $loop->index + 1 }}</td>
                <td class="fw-normal text-start" style="padding: 5px 2px 5px 5px">{{ $comment->comment }}</td>
            </tr>
        @endforeach
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
                <strong>{{ $settings['DGTT_CHAIRMAN']->Keyvalue }}</strong><br>
                {{ $settings['DGTT_CHAIRMAN_POSITION']->Keyvalue }}
            </td>
        </tr>
    </table>
</div>
</body>

