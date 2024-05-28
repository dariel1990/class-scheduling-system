<?php

namespace App\Http\Controllers;

use App\Services\DepartmentService;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;

class RoomController extends Controller
{
    protected $roomService;
    function __construct(RoomService $roomService,  private readonly DepartmentService $departmentService)
    {
        $this->roomService = $roomService;

        // $this->middleware('permission:room-read', ['only' => ['index']]);
        // $this->middleware('permission:room-create', ['only' => ['store']]);
        // $this->middleware('permission:room-update', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:room-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Rooms';
        $rooms = $this->roomService->getAllRoom();
        $departments = $this->departmentService->getAllDepartments();
        return view('admin.room.index', compact('rooms', 'pageTitle', 'departments'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->roomService->getAllRoom();
            return (new DataTables)->of($data)
                ->addColumn('department', function ($row) {
                    return $row->department->short_name;
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'room_name'     => 'required|unique:rooms,room_name',
            'room_type'     => 'required',
            'capacity'      => 'required',
            'department_id' => 'required',
        ]);

        $data = [
            'room_name'     => $request->room_name,
            'room_type'     => $request->room_type,
            'capacity'      => $request->capacity,
            'department_id' => $request->department_id,
        ];

        $this->roomService->createRoom($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->roomService->getRoomById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'room_name'     => 'required|unique:rooms,room_name,' . $id,
            'room_type'     => 'required',
            'capacity'      => 'required',
            'department_id' => 'required',
        ]);

        $data = [
            'room_name'  => $request->room_name,
            'room_type'   => $request->room_type,
            'capacity'      => $request->capacity,
            'department_id' => $request->department_id,
        ];

        $this->roomService->updateRoom($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        try {
            $this->roomService->deleteRoom($id);
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => $e]);
        }
    }
}
