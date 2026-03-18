<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use Illuminate\Http\Request;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// Authentication Views
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [SystemController::class , 'login'])->name('login.post');
Route::post('/reset-password', [SystemController::class , 'resetPassword'])->name('password.reset');
Route::post('/logout', [SystemController::class , 'logout'])->name('logout');

// Protected Dashboard and UI Routes
Route::middleware('auth')->group(function () {
    // SPA component views refactored to specific blades
    Route::get('/', function () {
            return view('dashboard.home'); }
        )->name('dashboard');
        Route::get('/schedules', function () {
            return view('schedules.schedules'); }
        )->name('schedules');
        Route::get('/teachers', function () {
            return view('teachers.teachers'); }
        )->name('teachers');
        Route::get('/comlabs-subjects', function () {
            return view('comlabs_subjects.comlabs_subjects'); }
        )->name('comlabs_subjects');    });

// API Endpoints
Route::get('/api/check', [SystemController::class , 'checkAuth']);
Route::post('/api/login', [SystemController::class , 'login']);
Route::get('/api/logout', [SystemController::class , 'logout']);

Route::middleware('auth')->group(function () {
    // Semesters
    Route::get('/api/semesters', [SystemController::class , 'getSemesters']);
    Route::post('/api/semesters', [SystemController::class , 'createSemester']);
    Route::put('/api/semesters/{id}/activate', [SystemController::class , 'activateSemester']);
    Route::delete('/api/semesters/{id}', [SystemController::class , 'deleteSemester']);

    // Rooms
    Route::get('/api/rooms', [SystemController::class , 'getRooms']);
    Route::post('/api/rooms', [SystemController::class , 'addRoom']);
    Route::put('/api/rooms/{id}', [SystemController::class , 'updateRoom']);
    Route::delete('/api/rooms/{id}', [SystemController::class , 'deleteRoom']);

    // Subjects
    Route::get('/api/subjects', [SystemController::class , 'getSubjects']);
    Route::post('/api/subjects', [SystemController::class , 'addSubject']);
    Route::put('/api/subjects/{id}', [SystemController::class , 'updateSubject']);
    Route::delete('/api/subjects/{id}', [SystemController::class , 'deleteSubject']);

    // Teachers (Faculty)
    Route::get('/api/faculty', [SystemController::class , 'getFaculty']);
    Route::post('/api/faculty', [SystemController::class , 'addFaculty']);
    Route::put('/api/faculty/{id}', [SystemController::class , 'updateFaculty']);
    Route::delete('/api/faculty/{id}', [SystemController::class , 'deleteFaculty']);

    // Schedules
    Route::get('/api/schedules', [SystemController::class , 'getSchedules']);
    Route::post('/api/schedules', [SystemController::class , 'addSchedule']);
    Route::put('/api/schedules/{id}', [SystemController::class , 'updateSchedule']);
    Route::delete('/api/schedules/{id}', [SystemController::class , 'deleteSchedule']);

    // Custom APIs for views
    Route::get('/api/teacher_schedule', [SystemController::class , 'getTeacherSchedule']);
    Route::get('/api/lab_schedule', [SystemController::class , 'getLabSchedule']);
});
