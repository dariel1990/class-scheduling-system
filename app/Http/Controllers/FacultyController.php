<?php

namespace App\Http\Controllers;

use App\Models\Faculties;
use App\Services\DepartmentService;
use App\Services\FacultyService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class FacultyController extends Controller
{
    protected $facultyService;
    protected $departmentService;
    function __construct(FacultyService $facultyService, DepartmentService $departmentService)
    {
        $this->facultyService = $facultyService;
        $this->departmentService = $departmentService;

        $this->middleware('permission:faculty-read', ['only' => ['index']]);
        $this->middleware('permission:faculty-create', ['only' => ['store']]);
        $this->middleware('permission:faculty-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:faculty-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Faculties';
        $departments = $this->departmentService->getAllDepartments();
        $faculties = $this->facultyService->getAllFaculties();
        return view('admin.faculties.index', compact('faculties', 'departments', 'pageTitle'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->facultyService->getAllFaculties();
            return (new DataTables)->of($data)
                ->addColumn('department', function ($row) {
                    return $row->department->description;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'last_name'             => 'required',
            'contact_no'            => 'required',
            'employment_status'     => 'required',
        ]);

        $data = [
            'department_id'         => $request->department_id,
            'first_name'            => strtoupper($request->first_name),
            'middle_name'           => strtoupper(substr($request->middle_name, 0, 1)),
            'last_name'             => strtoupper($request->last_name),
            'suffix'                => strtoupper($request->suffix),
            'contact_no'            => $request->contact_no,
            'employment_status'     => $request->employment_status,
        ];

        $this->facultyService->createFaculties($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->facultyService->getFacultiesById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'last_name'             => 'required',
            'contact_no'            => 'required',
            'employment_status'     => 'required',
        ]);

        $data = [
            'department_id'         => $request->department_id,
            'first_name'            => strtoupper($request->first_name),
            'middle_name'           => strtoupper(substr($request->middle_name, 0, 1)),
            'last_name'             => strtoupper($request->last_name),
            'suffix'                => strtoupper($request->suffix),
            'contact_no'            => $request->contact_no,
            'employment_status'     => $request->employment_status,
        ];

        $this->facultyService->updateFaculties($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->facultyService->deleteFaculties($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => $e]);
        }
    }
}
