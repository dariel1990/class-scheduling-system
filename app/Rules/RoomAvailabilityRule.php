<?php

namespace App\Rules;

use Closure;
use App\Models\AcademicYear;
use App\Models\ClassSchedule;
use Illuminate\Contracts\Validation\ValidationRule;

class RoomAvailabilityRule implements ValidationRule
{
    protected $start_time;
    protected $end_time;
    protected $week_days;

    public function __construct($start_time, $end_time, $week_days)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->week_days = $week_days;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($this->week_days as $week_day) {
            // Check if the room is available for each selected week day
            $available = ClassSchedule::where('room_id', $value)
                ->where('week_day', $week_day)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('start_time', '>=', $this->start_time)
                            ->where('start_time', '<', $this->end_time);
                    })->orWhere(function ($q) {
                        $q->where('end_time', '>', $this->start_time)
                            ->where('end_time', '<=', $this->end_time);
                    });
                })
                ->doesntExist();

            if (!$available) {
                $fail('The room is not available at the specified time.');
            }
        }
    }
}
