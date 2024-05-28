<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculties extends Model
{
    use HasFactory;

    public $table = 'faculties';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact_no',
        'department_id',
        'employment_status',
        'years_in_service',
        'educational_qualification',
        'major',
        'eligibility',
    ];

    public $appends = [
        'fullname',
    ];

    public function getFullnameAttribute()
    {
        return Str::upper($this->last_name) . ', ' . Str::upper($this->first_name) . ' ' . Str::upper($this->middle_name) . ' ' . Str::upper($this->suffix);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subjects::class, 'subject_assignments', 'faculty_id', 'subject_id')
            ->withTimestamps();
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'id');
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'faculty_id', 'id');
    }

    protected static function booted(): void
    {
        static::created(function (Faculties $faculty) {
            $fmLetters = substr($faculty->first_name, 0, 1) . substr($faculty->middle_name, 0, 1);
            $username = str_replace(' ', '', strtolower($fmLetters . $faculty->last_name));
            $password = 'nemsu_cantilan';
            $user = User::create([
                'username'      => $username,
                'password'      => bcrypt($password),
                'email'         => $username . '@nemsu.edu.ph',
            ]);

            $faculty->update(['user_id' => $user->id]);
            $role = Role::where('name', 'Faculty')->first();

            $user->assignRole([$role->id]);
        });

        static::deleting(function ($faculty) {
            $user = User::find($faculty->user_id);
            $user->delete();
        });
    }
}
