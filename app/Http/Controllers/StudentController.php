<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Students;
use App\Models\Subjects;
use Illuminate\Http\Request;
use App\Models\StudentSubject;
use App\Models\SubjectStudents;
use App\Services\StudentService;
use App\Services\SubjectService;
use Illuminate\Support\Facades\DB;
use App\Services\StudentSubjectService;
use App\Services\SubjectAssignmentService;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{
    protected $studentService;
    protected $studentSubjectService;
    protected $subjectAssignmentService;

    function __construct(
        StudentService $studentService,
        StudentSubjectService $studentSubjectService,
        SubjectAssignmentService $subjectAssignmentService
    ) {
        $this->studentService = $studentService;
        $this->studentSubjectService = $studentSubjectService;
        $this->subjectAssignmentService = $subjectAssignmentService;

        $this->middleware('permission:student-read', ['only' => ['index']]);
        $this->middleware('permission:student-create', ['only' => ['store']]);
        $this->middleware('permission:student-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:student-delete', ['only' => ['delete']]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'    => 'required',
            'middle_name'   => 'required',
            'last_name'     => 'required',
            'course'        => 'required',
            'year_level'    => 'required',
            'section'       => 'required',
        ]);

        $subjectId = $request->subject_id;
        $subjectCode = $this->subjectAssignmentService->getSubjectAssignmentById($subjectId);

        $existingStudent = $this->studentService->studentExist(
            strtoupper($request->last_name),
            strtoupper($request->first_name),
            strtoupper(substr($request->middle_name, 0, 1))
        );

        if ($existingStudent) {
            $existingSubjectStudent = $this->studentSubjectService->studentSubjectExist($subjectId, $existingStudent->id);

            if (!$existingSubjectStudent) {

                $data = [
                    'subject_id' => $subjectId,
                    'student_id' => $existingStudent->id,
                ];

                $this->studentSubjectService->createStudentSubject($data);
                $newCount = [
                    'student_population' => $subjectCode->student_population + 1,
                ];
                $this->subjectAssignmentService->updateSubjectAssignment($subjectId, $newCount);
            } else {
                return response()->json(['error' => 'Student already enrolled in this Subject']);
            }
            return response()->json(['success' => false, 'error' => 'Student already exists.']);
        }

        $studentData = [
            'first_name'    => strtoupper($request->first_name),
            'middle_name'   => strtoupper(substr($request->middle_name, 0, 1)),
            'last_name'     => strtoupper($request->last_name),
            'suffix'        => strtoupper($request->suffix),
            'course'        => $request->course,
            'year_level'    => $request->year_level,
            'section'       => $request->section,
        ];

        $record = $this->studentService->createStudent($studentData);

        $studentSubjectData = [
            'subject_id' => $subjectId,
            'student_id' => $record->id,
        ];

        $this->studentSubjectService->createStudentSubject($studentSubjectData);
        $newCount = [
            'student_population' => $subjectCode->student_population + 1,
        ];
        $this->subjectAssignmentService->updateSubjectAssignment($subjectId, $newCount);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->studentService->getStudentById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name'    => 'required',
            'middle_name'   => 'required',
            'last_name'     => 'required',
            'course'        => 'required',
            'year_level'    => 'required',
            'section'       => 'required',
        ]);

        $studentData = [
            'first_name'    => strtoupper($request->first_name),
            'middle_name'   => strtoupper(substr($request->middle_name, 0, 1)),
            'last_name'     => strtoupper($request->last_name),
            'suffix'        => strtoupper($request->suffix),
            'course'        => $request->course,
            'year_level'    => $request->year_level,
            'section'       => $request->section,
        ];

        $this->studentService->updateStudent($id, $studentData);

        return response()->json(['success' => true]);
    }

    public function delete($id, $subjectId)
    {
        try {
            $this->studentService->deleteStudent($id);
            $subjectCode = $this->subjectAssignmentService->getSubjectAssignmentById($subjectId);
            $newCount = [
                'student_population' => $subjectCode->student_population - 1,
            ];
            $this->subjectAssignmentService->updateSubjectAssignment($subjectId, $newCount);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }
}
