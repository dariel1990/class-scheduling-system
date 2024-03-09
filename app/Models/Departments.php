<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    public $table = 'departments';

    protected $fillable = [
        'short_name',
        'description',
        'program_head',
    ];

    public function faculty()
    {
        return $this->hasMany(Faculties::class, 'department_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'department_id', 'id');
    }
}
