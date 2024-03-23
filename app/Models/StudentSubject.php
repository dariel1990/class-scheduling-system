<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSubject extends Model
{
    use HasFactory;
    public $table = 'student_subjects';

    protected $fillable = [
        'student_id',
        'subject_id',
    ];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subjects()
    {
        return $this->belongsTo(SubjectAssignment::class, 'subject_id');
    }
}
