<?php

use App\Models\Faculties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\ClassSchedulerController;
use App\Http\Controllers\StudentSubjectController;
use App\Http\Controllers\SubjectStudentController;
use App\Http\Controllers\SubjectAssignmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth:web']], function () {
    Route::resource('users', UserController::class);
    Route::get('/users-list', [UserController::class, 'list'])->name('user.list');
    Route::get('/edit/profile', [UserController::class, 'editProfile'])->name('edit.profile');
    Route::put('/edit/profile/{id}', [UserController::class, 'updateProfile'])->name('update.profile');

    Route::resource('roles', RoleController::class);
    Route::get('/roles-list', [RoleController::class, 'list'])->name('role.list');

    //Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/update/{keyName}', [SettingsController::class, 'update']);

    //Academic Year
    Route::get('/academic/year', [AcademicYearController::class, 'index'])->name('academic.year');
    Route::get('/academic/year/list', [AcademicYearController::class, 'list'])->name('academic.year.list');
    Route::post('/academic/year/store', [AcademicYearController::class, 'store'])->name('academic.year.store');
    Route::get('/academic/year/edit/{id}', [AcademicYearController::class, 'edit'])->name('academic.year.edit');
    Route::put('/academic/year/{id}', [AcademicYearController::class, 'update'])->name('academic.year.update');
    Route::delete('/academic/year/{id}', [AcademicYearController::class, 'delete'])->name('academic.year.delete');
    Route::put('/academic-year-updateDefaultStatus/{id}', [AcademicYearController::class, 'updateDefaultStatus'])->name('academic.year.updateDefaultStatus');

    //Departments
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('/department/list', [DepartmentController::class, 'list'])->name('department.list');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('/department/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/department/{id}', [DepartmentController::class, 'delete'])->name('department.delete');

    //Classes
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');
    Route::get('/classes/list', [ClassesController::class, 'list'])->name('classes.list');
    Route::post('/classes/store', [ClassesController::class, 'store'])->name('classes.store');
    Route::get('/classes/edit/{id}', [ClassesController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{id}', [ClassesController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassesController::class, 'delete'])->name('classes.delete');

    //Subject
    Route::get('/subject', [SubjectController::class, 'index'])->name('subject.index');
    Route::get('/subject/list', [SubjectController::class, 'list'])->name('subject.list');
    Route::get('/get-all-subjects', [SubjectController::class, 'getAllSubjects']);
    Route::post('/subject/store', [SubjectController::class, 'store'])->name('subject.store');
    Route::get('/subject/edit/{id}', [SubjectController::class, 'edit'])->name('subject.edit');
    Route::put('/subject/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subject/{id}', [SubjectController::class, 'delete'])->name('subject.delete');

    //Subject Assignment
    Route::get('/subjects-assignments-to-class/{classId}', [SubjectAssignmentController::class, 'index'])->name('subject.assignment.index');
    Route::get('/subject-assignment/list/{classId}', [SubjectAssignmentController::class, 'list'])->name('subject.assignment.list');
    Route::post('/subject-assignment/store', [SubjectAssignmentController::class, 'store'])->name('subject.assignment.store');
    Route::get('/subject-assignment/edit/{id}', [SubjectAssignmentController::class, 'edit'])->name('subject.assignment.edit');
    Route::put('/subject-assignment/{id}', [SubjectAssignmentController::class, 'update'])->name('subject.assignment.update');
    Route::delete('/subject-assignment/{id}', [SubjectAssignmentController::class, 'delete'])->name('subject.assignment.delete');

    //StudentSubjects
    Route::get('/students/{subjectId}', [StudentSubjectController::class, 'index'])->name('subject.students');
    Route::post('/import/students', [StudentSubjectController::class, 'importStudents'])->name('students.import');

    //Students
    Route::post('/student', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/student/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/student/{id}/{subjectId}', [StudentController::class, 'delete'])->name('student.delete');

    //Rooms
    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::get('/room/list', [RoomController::class, 'list'])->name('room.list');
    Route::post('/room/store', [RoomController::class, 'store'])->name('room.store');
    Route::get('/room/edit/{id}', [RoomController::class, 'edit'])->name('room.edit');
    Route::put('/room/{id}', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/room/{id}', [RoomController::class, 'delete'])->name('room.delete');

    //Faculty
    Route::get('/faculty', [FacultyController::class, 'index'])->name('faculty.index');
    Route::get('/faculty/list', [FacultyController::class, 'list'])->name('faculty.list');
    Route::post('/faculty/store', [FacultyController::class, 'store'])->name('faculty.store');
    Route::get('/faculty/edit/{id}', [FacultyController::class, 'edit'])->name('faculty.edit');
    Route::put('/faculty/{id}', [FacultyController::class, 'update'])->name('faculty.update');
    Route::delete('/faculty/{id}', [FacultyController::class, 'delete'])->name('faculty.delete');

    //Classe Schedules
    Route::get('/classes-schedules', [ClassSchedulerController::class, 'index'])->name('class-schedules.index');
    Route::get('/classes-schedules/list', [ClassSchedulerController::class, 'list'])->name('classes.list');
    Route::post('/classes-schedules/store', [ClassSchedulerController::class, 'store'])->name('classes.store');
    Route::get('/classes-schedules/edit/{id}', [ClassSchedulerController::class, 'edit'])->name('classes.edit');
    Route::put('/classes-schedules/{id}', [ClassSchedulerController::class, 'update'])->name('classes.update');
    Route::delete('/classes-schedules/{id}', [ClassSchedulerController::class, 'delete'])->name('classes.delete');

    Route::get('/all/students/{faculty_id}', function ($faculty_id) {
        $faculty = Faculties::with(['subjects.students' => function ($query) {
            // Order students alphabetically by name
            $query->orderBy('last_name');
        }])->find($faculty_id);

        $uniqueStudentsIds = collect();

        // Loop through subjects and students to get unique students
        foreach ($faculty->subjects as $subject) {
            $uniqueStudentsIds = $uniqueStudentsIds->merge($subject->students->pluck('id'));
        }

        // Unique student IDs
        $uniqueStudentsIds = $uniqueStudentsIds->unique()->values()->toArray();
    });
});
