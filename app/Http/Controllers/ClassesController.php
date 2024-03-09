<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subjects;
use App\Models\Faculties;
use App\Models\Departments;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Services\ClassesService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Services\AcademicYearService;
use Illuminate\Database\QueryException;

class ClassesController extends Controller
{
    protected $departmentService;
    protected $classesService;
    protected readonly AcademicYearService $academicYearService;

    public function __construct(DepartmentService $departmentService, ClassesService $classesService)
    {
        $this->academicYearService = app()->make(AcademicYearService::class);
        $this->departmentService    = $departmentService;
        $this->classesService       = $classesService;

        $this->middleware('permission:class-list', ['only' => ['index']]);
        $this->middleware('permission:class-create', ['only' => ['store']]);
        $this->middleware('permission:class-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:class-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Classes';
        $defaultAY = $this->academicYearService->getDefaultPeriod();
        $departments = $this->departmentService->getAllDepartments();
        return view('admin.classes.index', compact('departments', 'defaultAY', 'pageTitle'));
    }

    public function list()
    {
        $defaultAY = $this->academicYearService->getDefaultPeriod();

        if (request()->ajax()) {
            $data = $this->classesService->getAllClassesByDefaultAcademicYear($defaultAY->id);
            return (new DataTables)->of($data)
                ->addColumn('short_name', function ($row) {
                    return $row->department->short_name;
                })
                ->addColumn('department', function ($row) {
                    return $row->department->description;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'department_id' => 'required',
            'class_code'    => 'required|unique:classes,class_code',
            'course'        => 'required',
            'year_level'    => 'required',
            'section'       => 'required',
        ]);

        $data = [
            'academic_id'   => $request->academic_id,
            'department_id' => $request->department_id,
            'class_code'    => $request->class_code,
            'course'        => strtoupper($request->course),
            'year_level'    => $request->year_level,
            'section'       => strtoupper($request->section),
            'major'         => strtoupper($request->major),
        ];

        $this->classesService->createClasses($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->classesService->getClassesById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'class_code'    => 'required|unique:classes,class_code,' . $id,
            'course'        => 'required',
            'year_level'    => 'required',
            'section'       => 'required',
        ]);

        $data = [
            'academic_id'   => $request->academic_id,
            'department_id' => $request->department_id,
            'class_code'    => $request->class_code,
            'course'        => strtoupper($request->course),
            'year_level'    => $request->year_level,
            'section'       => strtoupper($request->section),
            'major'         => strtoupper($request->major),
        ];

        $this->classesService->updateClasses($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->classesService->deleteClasses($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }
}
