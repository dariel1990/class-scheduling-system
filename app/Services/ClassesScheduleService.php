<?php

namespace App\Services;

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
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room')
            ->select(
                'sa_id',
                'academic_id',
                'room_id',
                'start_time',
                'end_time',
                DB::raw("GROUP_CONCAT(DISTINCT week_day ORDER BY week_day SEPARATOR '-') AS week_days")
            )
            ->where('academic_id', $defaultPeriod)
            ->groupBy('sa_id', 'academic_id', 'room_id', 'start_time', 'end_time')
            ->get();
    }

    public function getClassScheduleById($sa_id)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room')
            ->select(
                'sa_id',
                'academic_id',
                'room_id',
                'start_time',
                'end_time',
                DB::raw("GROUP_CONCAT(DISTINCT week_day ORDER BY week_day SEPARATOR '-') AS week_days")
            )
            ->where('sa_id', $sa_id)
            ->groupBy('sa_id', 'academic_id', 'room_id', 'start_time', 'end_time')
            ->get();
    }

    public function getClassScheduleByFacultyIdAndAcademicYear($facultyId, $academicId)
    {
        return ClassSchedule::with('subject_assignments', 'academic_year', 'room')
            ->whereHas('subject_assignments', function ($query) use ($facultyId) {
                $query->where('faculty_id', $facultyId);
            })
            ->select(
                'sa_id',
                'academic_id',
                'room_id',
                'start_time',
                'end_time',
                DB::raw("GROUP_CONCAT(DISTINCT week_day ORDER BY week_day SEPARATOR '-') AS week_days")
            )
            ->where('academic_id', $academicId)
            ->groupBy('sa_id', 'academic_id', 'room_id', 'start_time', 'end_time')
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
}
