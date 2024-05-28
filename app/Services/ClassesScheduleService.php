<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\ClassSchedule;
use Illuminate\Support\Facades\DB;

class ClassesScheduleService
{
    public function getAllClassSchedule()
    {
        return ClassSchedule::all();
    }

    public function getAllClassScheduleByDefaultAcademicYear($defaultPeriod)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room', 'time_slot')
            ->where('academic_id', $defaultPeriod)
            ->get();
    }

    public function getClassScheduleById($sa_id)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room', 'time_slot')
            ->where('sa_id', $sa_id)
            ->get();
    }

    public function getClassScheduleByFacultyIdAndAcademicYear($facultyId, $academicId)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room', 'time_slot')
            ->whereHas('subject_assignments', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })
            ->where('academic_id', $academicId)
            ->get();
    }

    public function getClassScheduleByStudentIdAndAcademicYear($studentId, $academicId)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room', 'time_slot')
            ->whereHas('subject_assignments', function ($query) use ($studentId) {
                $query->whereHas('students', function ($subQuery) use ($studentId) {
                    $subQuery->where('students.id', $studentId);
                });
            })
            ->where('academic_id', $academicId)
            ->get();
    }

    public function getClassScheduleByClassIdAndAcademicYear($classId, $academicId)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room', 'time_slot')
            ->whereHas('subject_assignments', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->where('academic_id', $academicId)
            ->get();
    }

    public function createClassSchedule(array $data)
    {
        return ClassSchedule::create($data);
    }

    public function updateClassSchedule($id, array $data)
    {
        $record = ClassSchedule::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteClassSchedule($sa_id)
    {
        ClassSchedule::where('sa_id', $sa_id)->get()->each->delete();
    }

    public function suggestVacantRoom($start_time, $end_time, $week_day)
    {
        // Convert start_time and end_time to Carbon instances for easier comparison
        $start_time = Carbon::parse($start_time);
        $end_time = Carbon::parse($end_time);

        // Query rooms that are available during the specified time slot and week day
        $availableRooms = Room::whereDoesntHave('class_schedules', function ($query) use ($start_time, $end_time, $week_day) {
            $query->where('week_day', $week_day)
                ->where(function ($query) use ($start_time, $end_time) {
                    $query->where(function ($query) use ($start_time, $end_time) {
                        $query->where('start_time', '>=', $start_time)
                            ->where('start_time', '<', $end_time);
                    })->orWhere(function ($query) use ($start_time, $end_time) {
                        $query->where('end_time', '>', $start_time)
                            ->where('end_time', '<=', $end_time);
                    });
                });
        })->get();

        // If there are available rooms, construct a suggestion message
        if ($availableRooms->isNotEmpty()) {
            $roomList = $availableRooms->pluck('room_name')->implode(', ');
            return "You can consider scheduling in one of the following rooms: $roomList.";
        } else {
            // If no available rooms are found, return a different suggestion or message
            return "Sorry, we couldn't find any vacant rooms for the specified time slot and day.";
        }
    }

    public function suggestVacantTimeSlotAndDayForRoom($room_id)
    {
        $room = Room::findOrFail($room_id);
        $roomSchedules = $room->class_schedules;

        if ($roomSchedules === null || $roomSchedules->isEmpty()) {
            return "No class schedules found for the selected room.";
        }

        $desiredDurationInMinutes = 60;
        $weekDays = [
            'M' => 'Monday',
            'Tu' => 'Tuesday',
            'W' => 'Wednesday',
            'Th' => 'Thursday',
            'F' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];

        $availableTimeSlots = [];

        $startTime = Carbon::createFromTime(7, 0, 0); // 7:00 AM
        $endTime = Carbon::createFromTime(21, 0, 0); // 9:00 PM

        foreach ($weekDays as $abbrev => $dayName) {
            $classSchedulesOnDay = $roomSchedules->filter(function ($schedule) use ($abbrev) {
                return $schedule->week_day === $abbrev;
            });

            $lastEndTime = clone $startTime;

            foreach ($classSchedulesOnDay as $schedule) {
                $startTime = Carbon::parse($schedule->start_time);
                $gap = $startTime->diffInMinutes($lastEndTime);

                if ($gap >= $desiredDurationInMinutes) {
                    $availableEndTime = min($schedule->start_time, $endTime);
                    $formattedLastEndTime = Carbon::parse($lastEndTime)->format('H:i');
                    $formattedAvailableEndTime = Carbon::parse($availableEndTime)->format('H:i');

                    $availableTimeSlots[] = "$dayName, from {$formattedLastEndTime} to {$formattedAvailableEndTime} is available for scheduling.";

                    $lastEndTime = $availableEndTime;
                }

                $lastEndTime = $schedule->end_time;
            }

            $gap = $endTime->diffInMinutes($lastEndTime);

            if ($gap >= $desiredDurationInMinutes) {
                $formattedLastEndTime = Carbon::parse($lastEndTime)->format('H:i');
                $formattedEndTime = Carbon::parse($endTime)->format('H:i');
                $availableTimeSlots[] = "$dayName, from {$formattedLastEndTime} to {$formattedEndTime} is available for scheduling.";
            }
        }

        // Return the suggestion paragraph
        return implode("\n", $availableTimeSlots);
    }
}
