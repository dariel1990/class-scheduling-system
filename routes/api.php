<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Services\ClassesScheduleService;
use App\Services\DepartmentService;
use App\Services\SubjectAssignmentService;
use App\Services\SubjectService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/not-selected/subjects/{classId}', [SubjectController::class, 'notSelectedSubjects']);
Route::get('/subjects/on-edit/{classId}/{subjectId}', [SubjectController::class, 'subjectsOnEdit']);

Route::get('/subject-assignment-no-cs/{classID}', function (SubjectAssignmentService $subjectAssignmentService, $classId) {
    return $subjectAssignmentService->getAllSubjectAssignmentByClassHasNoClassSchedules($classId);
});

Route::get('/subject-assignment-all/{classID}', function (SubjectAssignmentService $subjectAssignmentService, $classId) {
    return $subjectAssignmentService->getAllSubjectAssignmentByClass($classId);
});

Route::get('/subject-assigned-faculty/{saId}', function (SubjectAssignmentService $subjectAssignmentService, $saId) {
    return $subjectAssignmentService->getSubjectAssignmentById($saId);
});

Route::get('/suggest-vacancy-for-room/{roomId}', function (ClassesScheduleService $classesScheduleService, $roomId) {
    return $classesScheduleService->suggestVacantTimeSlotAndDayForRoom($roomId);
});

Route::get('/get-department-subjects/{id}', function (SubjectAssignmentService $subjectAssignmentService, $departmentId) {
    return $subjectAssignmentService->getAllSubjectAssignmentByDepartment($departmentId);
});

Route::get('/get-faculty-subjects/{id}', function (ClassesScheduleService $classesScheduleService, $roomId) {
    return $classesScheduleService->suggestVacantTimeSlotAndDayForRoom($roomId);
});

Route::get('/get-classes-subjects/{id}', function (ClassesScheduleService $classesScheduleService, $roomId) {
    return $classesScheduleService->suggestVacantTimeSlotAndDayForRoom($roomId);
});

Route::get('/get-rooms/{scheduleOption}/{id}', function (ClassesScheduleService $classesScheduleService, $roomId) {
    return $classesScheduleService->suggestVacantTimeSlotAndDayForRoom($roomId);
});

Route::get('/get-time-slots', function (ClassesScheduleService $classesScheduleService, $roomId) {
    return $classesScheduleService->suggestVacantTimeSlotAndDayForRoom($roomId);
});
