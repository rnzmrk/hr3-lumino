<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/timesheets-record', [TimesheetController::class, 'index'])->name('timesheets.record');
    Route::get('/timesheets-record/export', [TimesheetController::class, 'exportRecordCsv'])->name('timesheets.record.export');
    Route::get('/timesheets-report', [TimesheetController::class, 'report'])->name('timesheets.report');
    Route::get('/schedule-management', [ShiftController::class, 'showSchedule'])->name('schedule.management');
    Route::get('/schedule-management/export', [ShiftController::class, 'exportCsv'])->name('schedule.management.export');
    Route::get('/overtime', [OvertimeController::class, 'index'])->name('overtime.index');
    Route::get('/overtime/export', [OvertimeController::class, 'exportCsv'])->name('overtime.export');
    Route::get('/claims-reimbursement', [ClaimsController::class, 'index'])->name('claims.index');
    Route::get('/claims/create', [ClaimsController::class, 'create'])->name('claims.create');
    Route::post('/claims', [ClaimsController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}/edit', [ClaimsController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ClaimsController::class, 'update'])->name('claims.update');
    Route::delete('/claims/{claim}', [ClaimsController::class, 'destroy'])->name('claims.destroy');
    Route::get('/claims/export', [ClaimsController::class, 'exportCsv'])->name('claims.export');
    
    // Leave routes
    Route::get('/manage-leave', [LeaveController::class, 'manageLeave'])->name('leaves.manage');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::get('/leaves/export', [LeaveController::class, 'exportCsv'])->name('leaves.export');
    
    // Overtime routes
    Route::put('/overtime-requests/{attendance}/update', [OvertimeController::class, 'updateOvertimeRequest']);
    Route::put('/overtime-requests/{attendance}/status', [OvertimeController::class, 'updateStatus'])->name('overtime.update.status');
    
    // Attendance routes
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/export', [AttendanceController::class, 'exportCsv'])->name('attendance.export');
    Route::get('/employees/search-attendance', [AttendanceController::class, 'searchEmployees'])->name('employees.search.attendance');
    
    // Timesheet routes
    Route::get('/employees/search-timesheet', [TimesheetController::class, 'searchEmployees'])->name('employees.search.timesheet');
    Route::get('/employees/search-report', [TimesheetController::class, 'searchEmployeesReport'])->name('employees.search.report');
    Route::post('/employee-report', [TimesheetController::class, 'getEmployeeReport'])->name('employee.report');
    
    // Shift Web Routes
    Route::get('/shifts', [ShiftController::class, 'index']);
    Route::post('/shifts', [ShiftController::class, 'store']);
    Route::get('/shifts/{shift}/edit', [ShiftController::class, 'edit']);
    Route::put('/shifts/{shift}', [ShiftController::class, 'update']);
    Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy']);
    Route::get('/shifts/export', [ShiftController::class, 'exportCsv'])->name('shifts.export');
    
    // Schedule Management Routes
    Route::post('/schedule-management', [ShiftController::class, 'storeSchedule'])->name('schedule.management.store');
    Route::get('/schedule-management/{shift}/edit', [ShiftController::class, 'editSchedule'])->name('schedule.management.edit');
    Route::put('/schedule-management/{shift}', [ShiftController::class, 'updateSchedule'])->name('schedule.management.update');
    Route::delete('/schedule-management/{shift}', [ShiftController::class, 'destroySchedule'])->name('schedule.management.destroy');
    Route::get('/employees/search-schedule', [ShiftController::class, 'searchEmployeesSchedule'])->name('employees.search.schedule');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});