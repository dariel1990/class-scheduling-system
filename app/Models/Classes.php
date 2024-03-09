<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    public $table = 'classes';

    protected $fillable = [
        'academic_id',
        'department_id',
        'class_code',
        'course',
        'year_level',
        'section',
        'major',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subjects::class, 'subject_assignments', 'class_id', 'subject_id')
            ->withTimestamps();
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'id');
    }
}
