<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\ClassesService;
use App\Services\FacultyService;
use App\Services\SubjectService;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use App\Services\SubjectAssignmentService;

class SubjectAssignmentController extends Controller
{
    protected $subjectAssignmentService;
    protected $classesService;
    protected $subjectService;
    protected $facultyService;
    function __construct(
        SubjectAssignmentService $subjectAssignmentService,
        ClassesService $classesService,
        FacultyService $facultyService,
        SubjectService $subjectService
    ) {
        $this->subjectAssignmentService = $subjectAssignmentService;
        $this->classesService = $classesService;
        $this->facultyService = $facultyService;
        $this->subjectService = $subjectService;

        // $this->middleware('permission:subject-assignment-read', ['only' => ['index']]);
        // $this->middleware('permission:subject-assignment-create', ['only' => ['store']]);
        // $this->middleware('permission:subject-assignment-update', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:subject-assignment-delete', ['only' => ['delete']]);
    }

    public function index($classId)
    {
        $class = $this->classesService->getClassesById($classId);
        $subjects = $this->subjectService->getAllSubjects();
        $faculties = $this->facultyService->getAllFaculties();
        $pageTitle = "View Subject for " . $class->class_code;

        return view('admin.class-subjects.index', compact('class', 'subjects', 'pageTitle', 'faculties'));
    }

    public function list($classId)
    {
        if (request()->ajax()) {
            $data = $this->subjectAssignmentService->getAllSubjectAssignmentByClass($classId);
            return (new DataTables)->of($data)
                ->addColumn('subject', function ($row) {
                    return $row->subject->subject_code;
                })
                ->addColumn('description', function ($row) {
                    return $row->subject->description;
                })
                ->addColumn('faculty', function ($row) {
                    return $row->faculty->fullname;
                })
                ->addColumn('studentCount', function ($row) {
                    return $row->student_population;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject_id'  => 'required',
            'faculty_id'   => 'required',
        ]);

        $data = [
            'class_id'              => $request->class_id,
            'subject_id'            => $request->subject_id,
            'faculty_id'            => $request->faculty_id,
            'student_population'    => 0,
        ];

        $this->subjectAssignmentService->createSubjectAssignment($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->subjectAssignmentService->getSubjectAssignmentById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject_id'  => 'required',
            'faculty_id'   => 'required',
        ]);

        $data = [
            'class_id'  => $request->class_id,
            'subject_id'   => $request->subject_id,
            'faculty_id'      => $request->faculty_id,
        ];

        $this->subjectAssignmentService->updateSubjectAssignment($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->subjectAssignmentService->deleteSubjectAssignment($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => $e]);
        }
    }
}
