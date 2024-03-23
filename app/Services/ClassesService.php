<?php

namespace App\Services;

use App\Models\Classes;

class ClassesService
{
    public function getAllClasses()
    {
        return Classes::all();
    }

    public function getAllClassesByDefaultAcademicYear($defaultPeriod)
    {
        return Classes::with('department', 'subjects')->where('academic_id', $defaultPeriod)->get();
    }

    public function getClassesById($id)
    {
        return Classes::findOrFail($id);
    }

    public function createClasses(array $data)
    {
        return Classes::create($data);
    }

    public function updateClasses($id, array $data)
    {
        $record = Classes::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteClasses($id)
    {
        Classes::findOrFail($id)->delete();
    }
}
