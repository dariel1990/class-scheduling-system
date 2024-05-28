<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    public $table = 'class_schedules';

    protected $fillable = [
        'faculty_id',
        'sa_id',
        'academic_id',
        'room_id',
        'time_slot_id'
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

    public function faculty()
    {
        return $this->belongsTo(Faculties::class, 'faculty_id', 'id');
    }

    public function time_slot()
    {
        return $this->belongsTo(TimeSlots::class, 'time_slot_id', 'id');
    }
}
