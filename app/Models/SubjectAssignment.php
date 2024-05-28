<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
    use HasFactory;
    protected $table = 'subject_assignments';
    protected $fillable = ['subject_id', 'class_id', 'faculty_id', 'student_population', 'department_id'];
    public $with = ['subject', 'class', 'faculty'];

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculties::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects', 'subject_id', 'student_id');
    }

    public function class_schedule()
    {
        return $this->hasOne(ClassSchedule::class, 'sa_id', 'id');
    }
}
