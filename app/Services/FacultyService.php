<?php

namespace App\Services;

use App\Models\Faculties;

class FacultyService
{
    public function getAllFaculties()
    {
        return Faculties::with('department')->get();
    }

    public function getFacultiesById($id)
    {
        return Faculties::with('subjects', 'department')->where('id', $id)->first();
    }

    public function createFaculties(array $data)
    {
        return Faculties::create($data);
    }

    public function updateFaculties($id, array $data)
    {
        $record = Faculties::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteFaculties($id)
    {
        $record = Faculties::findOrFail($id)->delete();
    }
}
