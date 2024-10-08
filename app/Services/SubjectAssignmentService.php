<?php

namespace App\Services;

use App\Models\SubjectAssignment;

class SubjectAssignmentService
{
    public function getAllSubjectAssignment()
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students'])->get();
    }

    public function getAllSubjectAssignmentByDepartment($departmentId)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])
            ->whereDoesntHave('class_schedule')
            ->where('department_id', $departmentId)
            ->get();
    }

    public function getAllSubjectAssignmentByFaculty($facultyId)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])
            ->whereDoesntHave('class_schedule')
            ->where('faculty_id', $facultyId)
            ->get();
    }


    public function getAllSubjectAssignmentByClass($classId)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])
            ->where('class_id', $classId)
            ->get();
    }

    public function getAllSubjectAssignmentByClassHasNoClassSchedules($classId)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])
            ->where('class_id', $classId)
            ->whereDoesntHave('class_schedule')
            ->get();
    }

    public function getSubjectAssignmentById($id)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])->where('id', $id)->first();
    }

    public function getSubjectAssignmentByIdAll($id)
    {
        return SubjectAssignment::with(['subject', 'faculty', 'students', 'class'])->where('id', $id)->get();
    }


    public function createSubjectAssignment(array $data)
    {
        return SubjectAssignment::create($data);
    }

    public function updateSubjectAssignment($id, array $data)
    {
        $record = SubjectAssignment::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteSubjectAssignment($id)
    {
        SubjectAssignment::findOrFail($id)->delete();
    }
}
