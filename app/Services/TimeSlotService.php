<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\TimeSlots;
use Illuminate\Support\Facades\DB;

class TimeSlotService
{
    public function getAllTimeSlot()
    {
        return TimeSlots::all();
    }

    public function getTimeSlotById($id)
    {
        return TimeSlots::find($id);
    }

    public function createTimeSlot(array $data)
    {
        return TimeSlots::create($data);
    }

    public function updateTimeSlot($id, array $data)
    {
        $record = TimeSlots::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteTimeSlot($id)
    {
        TimeSlots::find($id)->delete();
    }
}
