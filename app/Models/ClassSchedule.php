<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    public $table = 'class_schedules';

    protected $fillable = [
        'sa_id',
        'academic_id',
        'room_id',
        'start_time',
        'end_time',
        'week_day',
    ];

    public function subject_assignments()
    {
        return $this->belongsTo(SubjectAssignment::class, 'sa_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
