<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Models\SubjectAssignment;
use App\Services\StudentSubjectService;
use App\Services\SubjectAssignmentService;
use Maatwebsite\Excel\Facades\Excel;

class StudentSubjectController extends Controller
{
    protected $subjectAssignmentService;
    protected $studentSubjectService;
    function __construct(
        SubjectAssignmentService $subjectAssignmentService,
        StudentSubjectService $studentSubjectService
    ) {
        $this->subjectAssignmentService = $subjectAssignmentService;
        $this->studentSubjectService = $studentSubjectService;

        $this->middleware('permission:student-read', ['only' => ['index']]);
        $this->middleware('permission:student-import', ['only' => ['importStudents']]);
    }

    public function index($subjectId)
    {
        $subject = $this->subjectAssignmentService->getSubjectAssignmentById($subjectId);
        $students = $subject->students()->orderBy('last_name', 'ASC')->get();

        return view('admin.student-subjects.index', compact('students', 'subject'));
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        $file = $request->file('file');
        $subjectId = $request->input('subject_id');
        $course = $request->input('course');
        $yearLevel = $request->input('year_level');
        $section = $request->input('section');

        // Use the AccountsImport class to import data from the Excel file
        Excel::import(new StudentImport($subjectId, $course, $yearLevel, $section), $file);

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
