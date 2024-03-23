<?php

namespace App\Services;

use App\Models\Student;
use App\Services\UserService;

class StudentService
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function studentExist($lastname, $firstname, $middlename)
    {
        return Student::with('subjects.subject')
            ->where('last_name', $lastname)
            ->where('first_name', $firstname)
            ->where('middle_name', $middlename)
            ->first();
    }

    public function getAllStudent()
    {
        return Student::with('subjects')->get();
    }

    public function getStudentById($id)
    {
        return Student::findOrFail($id);
    }

    public function createStudent(array $data)
    {
        return Student::create($data);
    }

    public function updateStudent($id, array $data)
    {
        $record = Student::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteStudent($id)
    {
        $user = $this->getStudentById($id);
        $this->userService->deleteUser($user->user_id);
    }
}
