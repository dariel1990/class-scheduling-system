<?php

namespace App\Models;

use App\Models\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjects extends Model
{
    use HasFactory;

    public $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'description',
        'category',
        'units',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'subject_assignment', 'subject_id', 'class_id')
            ->withTimestamps();
    }

    public function faculties()
    {
        return $this->belongsToMany(Faculties::class, 'subject_assignments', 'subject_id', 'faculty_id')
            ->withTimestamps();
    }
}
