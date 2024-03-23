<?php

namespace App\Services;

use App\Models\StudentSubject;

class StudentSubjectService
{
    public function studentSubjectExist($subjectId, $id): bool
    {
        return StudentSubject::where('subject_id', $subjectId)
            ->where('student_id', $id)
            ->exists();
    }

    public function getAllStudentSubject()
    {
        return StudentSubject::with(['subjects', 'students'])->get();
    }

    public function getStudentSubjectById($id)
    {
        return StudentSubject::findOrFail($id);
    }

    public function createStudentSubject(array $data)
    {
        return StudentSubject::create($data);
    }

    public function updateStudentSubject($id, array $data)
    {
        $record = StudentSubject::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteStudentSubject($id)
    {
        StudentSubject::findOrFail($id)->delete();
    }
}
