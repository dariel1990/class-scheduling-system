<?php

namespace App\Http\Controllers;

use App\Models\Faculties;
use App\Models\Departments;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use Illuminate\Database\QueryException;

class DepartmentController extends Controller
{
    protected $departmentService;

    function __construct(DepartmentService $departmentService)
    {
        $this->departmentService    = $departmentService;
        $this->middleware('permission:department-list', ['only' => ['index']]);
        $this->middleware('permission:department-create', ['only' => ['store']]);
        $this->middleware('permission:department-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:department-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Departments';
        $departments = $this->departmentService->getAllDepartments();

        return view('admin.departments.index', compact('departments', 'pageTitle'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->departmentService->getAllDepartments();
            return (new DataTables)->of($data)
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'short_name'        => 'required|unique:departments,short_name',
            'description'       => 'required',
            'program_head'      => 'required',
        ]);

        $data = [
            'short_name'        => $request->short_name,
            'description'       => $request->description,
            'program_head'      => $request->program_head,
        ];

        $this->departmentService->createDepartments($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->departmentService->getDepartmentsById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'short_name'        => 'required|unique:departments,short_name,' . $id,
            'description'       => 'required',
            'program_head'      => 'required',
        ]);

        $data = [
            'short_name'        => $request->short_name,
            'description'       => $request->description,
            'program_head'      => $request->program_head,
        ];

        $this->departmentService->updateDepartments($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->departmentService->deleteDepartments($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }
}
