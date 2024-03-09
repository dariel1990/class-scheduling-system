<?php

namespace App\Services;

use App\Models\Departments;

class DepartmentService
{
    public function getAllDepartments()
    {
        return Departments::all();
    }

    public function getDepartmentsById($id)
    {
        return Departments::findOrFail($id);
    }

    public function createDepartments(array $data)
    {
        return Departments::create($data);
    }

    public function updateDepartments($id, array $data)
    {
        $record = Departments::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteDepartments($id)
    {
        Departments::findOrFail($id)->delete();
    }
}
