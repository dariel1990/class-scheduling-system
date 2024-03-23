<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subjects;
use Illuminate\Http\Request;
use App\Services\SubjectService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SubjectController extends Controller
{
    protected $subjectService;
    function __construct(SubjectService $subjectService)
    {
        $this->subjectService       = $subjectService;

        $this->middleware('permission:subject-read', ['only' => ['index']]);
        $this->middleware('permission:subject-create', ['only' => ['store']]);
        $this->middleware('permission:subject-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subject-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Subjects';
        $subjects = $this->subjectService->getAllSubjects();
        return view('admin.subjects.index', compact('subjects', 'pageTitle'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->subjectService->getAllSubjects();
            return (new DataTables)->of($data)
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject_code'  => 'required|unique:subjects,subject_code',
            'description'   => 'required',
            'category'      => 'required',
            'units'         => 'required',
        ]);

        $data = [
            'subject_code'  => $request->subject_code,
            'description'   => $request->description,
            'category'      => $request->category,
            'units'         => $request->units,
        ];

        $this->subjectService->createSubjects($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->subjectService->getSubjectsById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject_code'  => 'required|unique:subjects,subject_code,' . $id,
            'description'   => 'required',
            'category'      => 'required',
            'units'         => 'required',
        ]);

        $data = [
            'subject_code'  => $request->subject_code,
            'description'   => $request->description,
            'category'      => $request->category,
            'units'         => $request->units,
        ];

        $this->subjectService->updateSubjects($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->subjectService->deleteSubjects($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => $e]);
        }
    }

    public function notSelectedSubjects($classId)
    {
        $class = Classes::find($classId);
        $subjectIds = $class->subjects()->pluck('subject_id')->toArray();

        $filteredSubjects = Subjects::whereNotIn('id', $subjectIds)->get();
        return $filteredSubjects;
    }

    public function subjectsOnEdit($classId, $subjectId)
    {
        $class = Classes::find($classId);
        $subjectIds = $class->subjects()->pluck('subject_id')->toArray();

        $filteredSubjectIds = array_filter($subjectIds, function ($id) use ($subjectId) {
            return $id != $subjectId;
        });

        $filteredSubjects = Subjects::whereNotIn('id', $filteredSubjectIds)->get();
        return $filteredSubjects;
    }

    public function getAllSubjects()
    {
        return $this->subjectService->getAllSubjects();
    }
}
