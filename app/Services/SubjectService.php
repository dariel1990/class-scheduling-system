<?php

namespace App\Services;

use App\Models\Subjects;

class SubjectService
{
    public function getAllSubjects()
    {
        return Subjects::all();
    }

    public function getSubjectsById($id)
    {
        return Subjects::findOrFail($id);
    }

    public function createSubjects(array $data)
    {
        return Subjects::create($data);
    }

    public function updateSubjects($id, array $data)
    {
        $record = Subjects::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteSubjects($id)
    {
        Subjects::findOrFail($id)->delete();
    }
}
