<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\TimeSlotService;
use Illuminate\Database\QueryException;

class TimeSlotController extends Controller
{
    protected $timeSlotService;
    function __construct(TimeSlotService $timeSlotService)
    {
        $this->timeSlotService = $timeSlotService;

        // $this->middleware('permission:slot-read', ['only' => ['index']]);
        // $this->middleware('permission:slot-create', ['only' => ['store']]);
        // $this->middleware('permission:slot-update', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:slot-delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'Time Slots';
        $rooms = $this->timeSlotService->getAllTimeSlot();

        $weekDays = [
            'M' => 'Monday',
            'Tu' => 'Tuesday',
            'W' => 'Wednesday',
            'Th' => 'Thursday',
            'F' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];

        return view('admin.time-slot.index', compact('rooms', 'pageTitle', 'weekDays'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = $this->timeSlotService->getAllTimeSlot();
            return (new DataTables)->of($data)
                ->addColumn('start_time', function ($row) {
                    return Carbon::parse($row->start_time)->format('h:i A');
                })
                ->addColumn('end_time', function ($row) {
                    return Carbon::parse($row->end_time)->format('h:i A');
                })
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'start_time'    => 'required',
            'end_time'      => 'required',
            'days'          => 'required',
        ]);

        $selectedDays = $request->input('days');

        if (!empty($selectedDays)) {
            $selectedDaysString = implode('-', $selectedDays);

            $selectedDaysString = ltrim($selectedDaysString, '-');
        }

        $data = [
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'days'          => $selectedDaysString,
        ];

        $this->timeSlotService->createTimeSlot($data);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        return $this->timeSlotService->getTimeSlotById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'start_time'    => 'required',
            'end_time'      => 'required',
            'days'          => 'required',
        ]);

        $selectedDays = $request->input('days');

        if (!empty($selectedDays)) {
            $selectedDaysString = implode('-', $selectedDays);

            $selectedDaysString = ltrim($selectedDaysString, '-');
        }

        $data = [
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'days'          => $selectedDaysString,
        ];


        $this->timeSlotService->updateTimeSlot($id, $data);

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $this->timeSlotService->deleteTimeSlot($id);
        return response()->json(['success' => true]);
    }
}
