<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\RoomService;
use App\Services\ClassesService;
use App\Services\FacultyService;
use App\Services\StudentService;
use Yajra\DataTables\DataTables;
use App\Services\TimeSlotService;
use Illuminate\Support\Facades\DB;
use App\Rules\RoomAvailabilityRule;
use App\Services\DepartmentService;
use App\Services\AcademicYearService;
use App\Rules\FacultyAvailabilityRule;
use Illuminate\Database\QueryException;
use App\Rules\ClassRoomAvailabilityRule;
use App\Services\ClassesScheduleService;
use App\Services\SubjectAssignmentService;
use Illuminate\Validation\ValidationException;

class ClassSchedulerController extends Controller
{
    protected $classScheduleService;
    public function __construct(
        private readonly AcademicYearService $academicYearService,
        private readonly RoomService $roomService,
        private readonly TimeSlotService $timeSlotService,
        private readonly StudentService $studentService,
        private readonly FacultyService $facultyService,
        private readonly SubjectAssignmentService $subjectAssignmentService,
        private readonly ClassesService $classesService,
        private readonly DepartmentService $departmentService,
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
        $timeSlots = $this->timeSlotService->getAllTimeSlot();
        $faculties = $this->facultyService->getAllFaculties();
        $students = $this->studentService->getAllStudent();

        return view(
            'admin.classes-schedules.index',
            compact('defaultAY', 'pageTitle', 'academicYears', 'rooms', 'classes', 'faculties', 'students', 'timeSlots')
        );
    }

    public function list()
    {
        $defaultAY = $this->academicYearService->getDefaultPeriod();

        if (request()->ajax()) {
            $data = $this->classScheduleService->getAllClassScheduleByDefaultAcademicYear($defaultAY->id);
            return (new DataTables)->of($data)
                ->addColumn('class', function ($row) {
                    return $row->subject_assignments->class->class_code;
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject_assignments->subject->subject_code;
                })
                ->addColumn('faculty', function ($row) {
                    return $row->subject_assignments->faculty->fullname;
                })
                ->addColumn('room', function ($row) {
                    return $row->room->room_name;
                })
                ->addColumn('time_slot', function ($row) {
                    $start_time = Carbon::parse($row->time_slot->start_time)->format('h:i a');
                    $end_time = Carbon::parse($row->time_slot->end_time)->format('h:i a');

                    return $start_time . ' - ' . $end_time;
                })
                ->addColumn('week_days', function ($row) {
                    return $row->time_slot->days;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'class_id'       => ['required', new ClassRoomAvailabilityRule($request->time_slot_id)],
            'sa_id'         => 'required|unique:class_schedules,sa_id',
            'room_id'       => ['required', new RoomAvailabilityRule($request->time_slot_id)],
            'time_slot_id'    => 'required',
            'faculty_id'    => ['required', new FacultyAvailabilityRule($request->time_slot_id)],
        ]);

        $data = [
            'sa_id'         => $request->sa_id,
            'academic_id'   => $request->academic_id,
            'room_id'       => $request->room_id,
            'time_slot_id'  => $request->time_slot_id,
            'faculty_id'    => $request->faculty_id,
        ];

        $this->classScheduleService->createClassSchedule($data);

        return response()->json(['success' => true]);
    }

    public function storeGroup(Request $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->academic_id as $index => $academicId) {
                // Create schedule details array
                $scheduleDetails = [
                    'sa_id'         => $request->sa_id[$index],
                    'academic_id'   => $academicId,
                    'room_id'       => $request->room_id[$index],
                    'time_slot_id'  => $request->time_slot_id[$index],
                    'faculty_id'    => $request->faculty_id[$index],
                ];

                // Create class schedule
                $this->classScheduleService->createClassSchedule($scheduleDetails);
            }
        });
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
            'room_id'       => ['required', new RoomAvailabilityRule($request->start_time, $request->end_time, $request->week_day, $request->faculty_id)],
            'start_time'    => 'required',
            'end_time'      => 'required|after:start_time',
            'week_day'      => 'required|array',
            'faculty_id'    => ['required', new FacultyAvailabilityRule($request->start_time, $request->end_time, $request->week_day)],
        ]);

        foreach ($request->week_day as $index =>  $weekDay) {

            $data = [
                'sa_id'         => $sa_id,
                'academic_id'   => $request->academic_id,
                'room_id'       => $request->room_id,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'faculty_id'    => $request->faculty_id,
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

    public function geneticAlgorithm()
    {
        $departments = $this->departmentService->getAllDepartments();
        $faculties = $this->facultyService->getAllFaculties();
        $defaultAY = $this->academicYearService->getDefaultPeriod();
        $classes = $this->classesService->getAllClassesByDefaultAcademicYear($defaultAY->id);

        return view('admin.genetic-algorithm.index', compact('departments', 'faculties', 'classes'));
    }
}
