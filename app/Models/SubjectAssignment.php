<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
    use HasFactory;
    protected $table = 'subject_assignments';
    protected $fillable = ['subject_id', 'class_id', 'faculty_id', 'student_population'];

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
}
