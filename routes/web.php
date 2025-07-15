<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientRequestController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AppointmentRequestController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientEditRequestController;
use App\Http\Controllers\Admin\PatientRequestController as AdminPatientRequestController;

// Redirect to dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Doctor Management
    Route::resource('doctors', DoctorController::class);
    
    // Patient Management  
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/request-edit', [PatientController::class, 'requestEdit'])->name('patients.request-edit');
    Route::post('/patients/{patient}/request-edit', [PatientController::class, 'storeEditRequest'])->name('patients.store-edit-request');
    
    // Patient Request Routes for Users
    Route::get('/patient/request/create', [PatientRequestController::class, 'create'])->name('patient.request.create');
    Route::post('/patient/request/create', [PatientRequestController::class, 'store'])->name('patient.request.store');
    Route::get('/patient/request/status', [PatientRequestController::class, 'status'])->name('patient.request.status');
    
    // Schedule Management
    Route::resource('schedules', ScheduleController::class);;
    
    // Appointment Management
    Route::resource('appointments', AppointmentController::class);
    
    // Appointment Request Routes for Users
    Route::get('/appointments/request/create', [AppointmentController::class, 'requestCreate'])->name('appointments.request.create');
    Route::post('/appointments/request/create', [AppointmentController::class, 'storeAppointmentRequest'])->name('appointments.request.store');
    Route::get('/appointments/{appointment}/request-edit', [AppointmentController::class, 'requestEdit'])->name('appointments.request.edit');
    Route::post('/appointments/{appointment}/request-edit', [AppointmentController::class, 'storeEditRequest'])->name('appointments.request.update');
    
    // Admin Appointment Request Routes
    Route::get('/appointment-requests', [AppointmentRequestController::class, 'index'])->name('appointment-requests.index');
    Route::get('/appointment-requests/{request}', [AppointmentRequestController::class, 'show'])->name('appointment-requests.show');
    Route::post('/appointment-requests/{request}/approve', [AppointmentRequestController::class, 'approve'])->name('appointment-requests.approve');
    Route::post('/appointment-requests/{request}/reject', [AppointmentRequestController::class, 'reject'])->name('appointment-requests.reject');
    
    // Excel Import Routes
    Route::get('/import', [ExcelImportController::class, 'index'])->name('import.index');
    Route::post('/import/doctors', [ExcelImportController::class, 'importDoctors'])->name('import.doctors');
    Route::post('/import/patients', [ExcelImportController::class, 'importPatients'])->name('import.patients');
    Route::post('/import/schedules', [ExcelImportController::class, 'importSchedules'])->name('import.schedules');
    
    // API Routes for AJAX calls
    Route::get('/api/doctors/{doctor}/schedules', [ScheduleController::class, 'getDoctorSchedules'])->name('api.doctor.schedules');
    Route::get('/api/schedules/{schedule}/available-times', [AppointmentController::class, 'getAvailableTimes'])->name('api.schedule.times');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        
        // Patient Edit Requests
        Route::get('/edit-requests', [PatientEditRequestController::class, 'index'])->name('edit-requests.index');
        Route::get('/edit-requests/{request}', [PatientEditRequestController::class, 'show'])->name('edit-requests.show');
        Route::post('/edit-requests/{request}/approve', [PatientEditRequestController::class, 'approve'])->name('edit-requests.approve');
        Route::post('/edit-requests/{request}/reject', [PatientEditRequestController::class, 'reject'])->name('edit-requests.reject');
        
        // Patient Requests
        Route::get('/patient-requests', [AdminPatientRequestController::class, 'index'])->name('patient-requests.index');
        Route::get('/patient-requests/{request}', [AdminPatientRequestController::class, 'show'])->name('patient-requests.show');
        Route::post('/patient-requests/{request}/approve', [AdminPatientRequestController::class, 'approve'])->name('patient-requests.approve');
        Route::post('/patient-requests/{request}/reject', [AdminPatientRequestController::class, 'reject'])->name('patient-requests.reject');
    });
});
