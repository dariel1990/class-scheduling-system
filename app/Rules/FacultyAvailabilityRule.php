<?php

namespace App\Rules;

use Closure;
use App\Models\TimeSlots;
use App\Models\ClassSchedule;
use Illuminate\Contracts\Validation\ValidationRule;

class FacultyAvailabilityRule implements ValidationRule
{
    protected $timeslot;

    public function __construct($timeslot)
    {
        $this->timeslot = $timeslot;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Retrieve the TimeSlot model associated with the timeslot id
        $timeSlot = TimeSlots::find($this->timeslot);

        // Check if the timeslot exists
        if (!$timeSlot) {
            $fail('Invalid time slot');
            return;
        }

        // Retrieve start_time and end_time from the TimeSlot model
        $start_time = $timeSlot->start_time;
        $end_time = $timeSlot->end_time;
        $days = $timeSlot->days;

        $available = ClassSchedule::where('faculty_id', $value)
            ->where(function ($query) use ($start_time, $end_time, $days) {
                $query->whereHas('time_slot', function ($subQuery) use ($start_time, $end_time, $days) {
                    $subQuery->where('days', $days)
                        ->where(function ($subSubQuery) use ($start_time, $end_time) {
                            $subSubQuery->where(function ($subSubSubQuery) use ($start_time, $end_time) {
                                $subSubSubQuery->where('start_time', '>=', $start_time)
                                    ->where('start_time', '<', $end_time);
                            })->orWhere(function ($subSubSubQuery) use ($start_time, $end_time) {
                                $subSubSubQuery->where('end_time', '>', $start_time)
                                    ->where('end_time', '<=', $end_time);
                            });
                        });
                });
            })
            ->doesntExist();

        if (!$available) {
            $fail('Faculty is not available on the specified time.');
        }
    }
}
