<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Services\RoomService;
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

        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->loadView(
            'admin.reports.faculty-workload',
            compact(
                'defaultPeriod',
                'faculty',
                'settings',
                'schedules',
            )
        )
            ->setOrientation('portrait')
            ->setOption('page-width', '215.9')
            ->setOption('page-height', '330.2');

        return $pdf->inline();
    }

    public function printStudentLoad($evaluationId)
    {
        $settings = [
            'SCHOOL_NAME'                           => Settings::where('Keyname', 'SCHOOL_NAME')->first(),
            'CAMPUS_NAME'                           => Settings::where('Keyname', 'CAMPUS_NAME')->first(),
            'CAMPUS_ADDRESS'                        => Settings::where('Keyname', 'CAMPUS_ADDRESS')->first(),
            'HR'                                    => Settings::where('Keyname', 'HR')->first(),
            'HR_POSITION'                           => Settings::where('Keyname', 'HR_POSITION')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR'             => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR')->first(),
            'ASSISTANT_CAMPUS_DIRECTOR_POSITION'    => Settings::where('Keyname', 'ASSISTANT_CAMPUS_DIRECTOR_POSITION')->first(),
            'DGTT_CHAIRMAN'                         => Settings::where('Keyname', 'DGTT_CHAIRMAN')->first(),
            'DGTT_CHAIRMAN_POSITION'                => Settings::where('Keyname', 'DGTT_CHAIRMAN_POSITION')->first(),
        ];

        $defaultPeriod = $this->academicYearService->getDefaultPeriod();
        $evaluation = $this->evaluationService->getEvaluationById($evaluationId);
        $evaluationType = '';
        if ($evaluation->type == 'Student') {
            $comments = $this->studentAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Student';
        } else if ($evaluation->type == 'Peer') {
            $comments = $this->peerAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Peer';
        } else if ($evaluation->type == 'Supervisor') {
            $comments = $this->supervisorAssignedEvaluation->getCommentsByEvaluationId($evaluationId);
            $evaluationType = 'Supervisor';
        }

        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->loadView(
            'admin.reports.comments-pdf',
            compact('defaultPeriod', 'evaluation', 'comments', 'evaluationId', 'settings', 'evaluationType')
        )
            ->setOrientation('portrait')
            ->setOption('page-width', '215.9')
            ->setOption('page-height', '330.2');

        return $pdf->inline();
    }
}
