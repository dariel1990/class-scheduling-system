<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Subjects;
use App\Models\Faculties;
use App\Models\Departments;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use App\Services\AcademicYearService;
use Illuminate\Support\Facades\Auth;
use App\Services\ClassesScheduleService;
use App\Services\FacultyService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:dashboard-read', ['only' => ['index', 'studentDashboard', 'facultyDashboard']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $instructorCount = Faculties::get()->count();
        $studentCount = Student::get()->count();
        $subjectCount = Subjects::get()->count();
        $classCount = Classes::get()->count();
        $departmentCount = Departments::get()->count();
        $roomCount = Room::get()->count();
        $userCount = User::get()->count();
        $pageTitle = 'Dashboard';
        return view('admin.home', compact(
            'pageTitle',
            'instructorCount',
            'studentCount',
            'subjectCount',
            'classCount',
            'departmentCount',
            'roomCount',
            'userCount'
        ));
    }

    public function studentDashboard(
        StudentService $studentService,
        ClassesScheduleService $classesScheduleService,
        AcademicYearService $academicYearService
    ) {
        $pageTitle = 'Dashboard';
        $defaultPeriod = $academicYearService->getDefaultPeriod();
        $student = $studentService->getStudentById(Auth::user()->student->id);
        $schedules = $classesScheduleService->getClassScheduleByStudentIdAndAcademicYear($student->id, $defaultPeriod->id);
        return view('users.studentDashboard', compact('pageTitle', 'defaultPeriod', 'student', 'schedules'));
    }

    public function facultyDashboard(
        FacultyService $facultyService,
        ClassesScheduleService $classesScheduleService,
        AcademicYearService $academicYearService
    ) {
        $defaultPeriod = $academicYearService->getDefaultPeriod();
        $faculty = $facultyService->getFacultiesById(Auth::user()->faculty->id);
        $schedules = $classesScheduleService->getClassScheduleByFacultyIdAndAcademicYear($faculty->id, $defaultPeriod->id);

        $pageTitle = 'Dashboard';
        return view('users.facultyDashboard', compact('pageTitle',  'defaultPeriod', 'faculty', 'schedules'));
    }
}
