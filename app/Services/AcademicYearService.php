<?php

namespace App\Services;

use App\Models\AcademicYear;

class AcademicYearService
{
    public function getAllPeriod()
    {
        return AcademicYear::all();
    }

    public function getDefaultPeriod()
    {
        return AcademicYear::where('isDefault', true)->first();
    }

    public function getPeriodById($id)
    {
        return AcademicYear::findOrFail($id);
    }

    public function createPeriod(array $data)
    {
        return AcademicYear::create($data);
    }

    public function updatePeriod($id, array $data)
    {
        $record = AcademicYear::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deletePeriod($id)
    {
        AcademicYear::findOrFail($id)->delete();
    }

    public function isDefault($id)
    {
        return AcademicYear::where('id', $id)->where('isDefault', true)->exists();
    }

    // Add more business logic related to tasks as needed
}
