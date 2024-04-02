<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Services\ClassesService;
use Yajra\DataTables\DataTables;
use App\Rules\RoomAvailabilityRule;
use App\Services\AcademicYearService;
use Illuminate\Database\QueryException;
use App\Services\ClassesScheduleService;
use App\Services\FacultyService;
use App\Services\StudentService;
use App\Services\SubjectAssignmentService;

class ClassSchedulerController extends Controller
{
    protected $classScheduleService;
    public function __construct(
        private readonly AcademicYearService $academicYearService,
        private readonly RoomService $roomService,
        private readonly StudentService $studentService,
        private readonly FacultyService $facultyService,
        private readonly SubjectAssignmentService $subjectAssignmentService,
        private readonly ClassesService $classesService,
        ClassesScheduleService $classesScheduleService,
    ) {
        $this->classScheduleService = $classesScheduleService;

        $this->middleware('permission:class-read', ['only' => ['index']]);
        $this->middleware('permission:class-create', ['only' => ['store']]);
        $this->middleware('permission:class-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:class-delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $pageTitle = 'Class Schedules';
        $defaultAY = $this->academicYearService->getDefaultPeriod();
        $classes = $this->classesService->getAllClassesByDefaultAcademicYear($defaultAY->id);
        $academicYears = $this->academicYearService->getAllPeriod();
        $rooms = $this->roomService->getAllRoom();
        $faculties = $this->facultyService->getAllFaculties();
        $students = $this->studentService->getAllStudent();

        $weekDays = [
            'M' => 'Monday',
            'Tu' => 'Tuesday',
            'W' => 'Wednesday',
            'Th' => 'Thursday',
            'F' => 'Friday',
            'Sa' => 'Saturday',
            'Su' => 'Sunday'
        ];

        return view(
            'admin.classes-schedules.index',
            compact('defaultAY', 'pageTitle', 'academicYears', 'rooms', 'classes', 'weekDays', 'faculties', 'students')
        );
    }

    public function list()
    {
        $defaultAY = $this->academicYearService->getDefaultPeriod();

        if (request()->ajax()) {
            $data = $this->classScheduleService->getAllClassScheduleByDefaultAcademicYear($defaultAY->id);
            return (new DataTables)->of($data)
                ->addColumn('subject', function ($row) {
                    return $row->subject_assignments->subject->subject_code;
                })
                ->addColumn('faculty', function ($row) {
                    return $row->subject_assignments->faculty->fullname;
                })
                ->addColumn('room', function ($row) {
                    return $row->room->room_name;
                })
                ->addColumn('schedule', function ($row) {
                    $start_time = Carbon::parse($row->start_time)->format('h:i a');
                    $end_time = Carbon::parse($row->end_time)->format('h:i a');

                    return $start_time . ' - ' . $end_time;
                })
                ->addColumn('week_days', function ($row) {
                    return $row->week_days;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'sa_id'         => 'required|unique:class_schedules,sa_id',
            'room_id'       => ['required', new RoomAvailabilityRule($request->start_time, $request->end_time, $request->week_day)],
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'week_day'      => 'required|array',
        ], [
            'end_time.after' => 'End time should be after the start time.'
        ]);

        foreach ($request->week_day as $index =>  $weekDay) {

            $data = [
                'sa_id'         => $request->sa_id,
                'academic_id'   => $request->academic_id,
                'room_id'       => $request->room_id,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'week_day'      => $weekDay,
            ];

            $this->classScheduleService->createClassSchedule($data);
        }

        return response()->json(['success' => true]);
    }

    public function edit($sa_id)
    {
        return $this->classScheduleService->getClassScheduleById($sa_id);
    }

    public function update(Request $request, $sa_id)
    {
        // dd($request->all());
        $this->classScheduleService->deleteClassSchedule($sa_id);

        $this->validate($request, [
            'room_id'       => ['required', new RoomAvailabilityRule($request->start_time, $request->end_time, $request->week_day)],
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'week_day'      => 'required|array',
        ]);

        foreach ($request->week_day as $index =>  $weekDay) {

            $data = [
                'sa_id'         => $sa_id,
                'academic_id'   => $request->academic_id,
                'room_id'       => $request->room_id,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'week_day'      => $weekDay,
            ];

            $this->classScheduleService->createClassSchedule($data);
        }
        return response()->json(['success' => true]);
    }

    public function delete($sa_id)
    {
        try {
            $this->classScheduleService->deleteClassSchedule($sa_id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }
}
