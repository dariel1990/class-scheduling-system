<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlots extends Model
{
    use HasFactory;

    public $table = 'time_slots';

    protected $fillable = [
        'start_time',
        'end_time',
        'days',
    ];

    public function class_schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'time_slot_id', 'id');
    }
}
