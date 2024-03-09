<?php

namespace App\Http\Controllers;

use App\Services\RoomService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;

class RoomController extends Controller
{
    protected $roomService;
    function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;

        $this->middleware('permission:subject-list', ['only' => ['index']]);
        $this->middleware('permission:subject-create', ['only' => ['store']]);
        $this->middleware('permission:subject-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subject-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Rooms';
        $rooms = $this->roomService->getAllRoom();
        return view('admin.room.index', compact('rooms', 'pageTitle'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->roomService->getAllRoom();
            return (new DataTables)->of($data)
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'room_name'     => 'required|unique:rooms,room_name',
            'room_type'     => 'required',
            'capacity'      => 'required',
        ]);

        $data = [
            'room_name'  => $request->room_name,
            'room_type'   => $request->room_type,
            'capacity'      => $request->capacity,
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
        ]);

        $data = [
            'room_name'  => $request->room_name,
            'room_type'   => $request->room_type,
            'capacity'      => $request->capacity,
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
