<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getLoggedInUser()
    {
        return Auth::user();
    }

    public function getAllUserExcludingLoggedInUser($userId)
    {
        return User::with('faculty', 'student')->where('id', '!=', $userId)->get();
    }

    public function getAllUser()
    {
        return User::get();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser($id, array $data)
    {
        $record = User::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
    }
}
