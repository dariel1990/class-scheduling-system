<?php

namespace App\Services;

use App\Models\Room;

class RoomService
{
    public function getAllRoom()
    {
        return Room::all();
    }

    public function getRoomById($id)
    {
        return Room::findOrFail($id);
    }

    public function createRoom(array $data)
    {
        return Room::create($data);
    }

    public function updateRoom($id, array $data)
    {
        $record = Room::findOrFail($id);
        $record->update($data);

        return $record;
    }

    public function deleteRoom($id)
    {
        Room::findOrFail($id)->delete();
    }
}
