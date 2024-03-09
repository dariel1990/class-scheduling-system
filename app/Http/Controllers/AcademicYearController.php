<?php

namespace App\Http\Controllers;

use App\Models\Faculties;
use App\Models\Evaluation;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Rules\DuplicatedAcademicYear;
use App\Services\AcademicYearService;
use Illuminate\Database\QueryException;

class AcademicYearController extends Controller
{
    protected $evaluationService;
    protected $academicYearService;

    public function __construct(AcademicYearService $academicYearService)
    {
        $this->academicYearService  = $academicYearService;

        $this->middleware('permission:period-list', ['only' => ['index']]);
        $this->middleware('permission:period-create', ['only' => ['store']]);
        $this->middleware('permission:period-edit', ['only' => ['edit', 'update', 'updateDefaultStatus']]);
        $this->middleware('permission:period-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Academic Year';
        $data = $this->academicYearService->getAllPeriod();
        return view('admin.academic-year.index', compact('data', 'pageTitle'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->academicYearService->getAllPeriod();
            return (new DataTables)->of($data)
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validationRules = [
            'semester' => 'required',
        ];

        if ($request->semester !== null) {
            $validationRules['academic_year'] = ['required', 'unique:academic_years', new DuplicatedAcademicYear($request->semester)];
        } else {
            $validationRules['academic_year'] = 'required|unique:academic_years';
        }

        $this->validate($request, $validationRules);

        $data = [
            'academic_year'     => $request->academic_year,
            'semester'          => $request->semester,
        ];

        $this->academicYearService->createPeriod($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->academicYearService->getPeriodById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'academic_year' => [
                'required',
                'unique:academic_years,academic_year,' . $id
            ],
            'semester'      => 'required',
        ]);

        $data = [
            'academic_year'     => $request->academic_year,
            'semester'          => $request->semester,
        ];

        $this->academicYearService->updatePeriod($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $this->academicYearService->deletePeriod($id);
        return response()->json(['success' => true]);
    }

    public function updateDefaultStatus(Request $request, $id)
    {
        $currentDefaultRow = $this->academicYearService->getDefaultPeriod();

        if ($currentDefaultRow) {
            $this->academicYearService->updatePeriod($currentDefaultRow->id, ['isDefault' => false]);
        }

        $this->academicYearService->updatePeriod($id, ['isDefault' => true]);

        return response()->json(['success' => true]);
    }
}
