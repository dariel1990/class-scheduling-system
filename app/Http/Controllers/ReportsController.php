<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Criteria;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Services\RoomService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ClassesService;
use App\Services\FacultyService;
use App\Services\StudentService;
use Illuminate\Support\Facades\App;
use App\Services\AcademicYearService;
use App\Services\ClassesScheduleService;
use App\Services\SubjectAssignmentService;

class ReportsController extends Controller
{
    public function __construct(
        private readonly AcademicYearService $academicYearService,
        private readonly RoomService $roomService,
        private readonly StudentService $studentService,
        private readonly FacultyService $facultyService,
        private readonly SubjectAssignmentService $subjectAssignmentService,
        private readonly ClassesService $classesService,
        private readonly ClassesScheduleService $classesScheduleService
    ) {
    }

    public function printFacultyWorkload($facultyId, $academicId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'CAMPUS_DIRECTOR'                       => Settings::where('Keyname', 'CAMPUS_DIRECTOR')->first(),
            'CAMPUS_DIRECTOR_POSITION'              => Settings::where('Keyname', 'CAMPUS_DIRECTOR_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getPeriodById($academicId);
        $faculty = $this->facultyService->getFacultiesById($facultyId);
        $schedules = $this->classesScheduleService->getClassScheduleByFacultyIdAndAcademicYear($facultyId, $academicId);

        $pdf = Pdf::loadView('admin.reports.faculty-workload', compact(
            'defaultPeriod',
            'faculty',
            'settings',
            'schedules',
        ))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }

    public function printStudentLoad($studentId, $academicId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'CAMPUS_DIRECTOR'                       => Settings::where('Keyname', 'CAMPUS_DIRECTOR')->first(),
            'CAMPUS_DIRECTOR_POSITION'              => Settings::where('Keyname', 'CAMPUS_DIRECTOR_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getPeriodById($academicId);
        $student = $this->studentService->getStudentById($studentId);
        $schedules = $this->classesScheduleService->getClassScheduleByStudentIdAndAcademicYear($studentId, $academicId);

        $pdf = Pdf::loadView('admin.reports.student-subjectload', compact(
            'defaultPeriod',
            'student',
            'settings',
            'schedules',
        ))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }

    public function printClassLoad($classId, $academicId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'CAMPUS_DIRECTOR'                       => Settings::where('Keyname', 'CAMPUS_DIRECTOR')->first(),
            'CAMPUS_DIRECTOR_POSITION'              => Settings::where('Keyname', 'CAMPUS_DIRECTOR_POSITION')->first(),
            'REGISTRAR'                             => Settings::where('Keyname', 'REGISTRAR')->first(),
            'REGISTRAR_POSITION'                    => Settings::where('Keyname', 'REGISTRAR_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getPeriodById($academicId);
        $classes = $this->classesService->getClassesById($classId);
        $schedules = $this->classesScheduleService->getClassScheduleByClassIdAndAcademicYear($classId, $academicId);

        // Grouping schedules by days and then by AM/PM
        $groupedSchedules = [];
        foreach ($schedules as $sched) {
            $days = $sched->time_slot->days;
            $startTime = Carbon::parse($sched->time_slot->start_time);
            $timeOfDay = $startTime->format('A'); // AM or PM

            if (!isset($groupedSchedules[$days])) {
                $groupedSchedules[$days] = ['AM' => [], 'PM' => []];
            }
            $groupedSchedules[$days][$timeOfDay][] = $sched;
        }


        $pdf = Pdf::loadView('admin.reports.class-subjectload', compact(
            'defaultPeriod',
            'classes',
            'settings',
            'schedules',
            'groupedSchedules'
        ))->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }
}
