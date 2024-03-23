<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $fillable = ['room_name', 'room_type', 'capacity'];

    public function class_schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'room_id', 'id');
    }
}
