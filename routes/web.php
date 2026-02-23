<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClaimsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TestController;
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
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('/test-shift', function() {
        try {
            $employee = \App\Models\Employee::first();
            if (!$employee) {
                return 'No employees found';
            }
            
            $shift = \App\Models\Shift::create([
                'employee_id' => $employee->id,
                'employee_name' => $employee->employee_name,
                'shift_type' => 'Morning Shift',
                'schedule_start' => '09:00',
                'schedule_end' => '17:00',
                'days' => 'Monday, Tuesday, Wednesday',
            ]);
            
            return 'Shift created successfully with ID: ' . $shift->id;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    });
    Route::get('/test-audit', [TestController::class, 'testAudit'])->name('test.audit');
    Route::get('/overtime', [OvertimeController::class, 'index'])->name('overtime.index');
    Route::get('/overtime/export', [OvertimeController::class, 'exportCsv'])->name('overtime.export');
    Route::get('/claims-reimbursement', [ClaimsController::class, 'index'])->name('claims.index');
    Route::get('/claims/create', [ClaimsController::class, 'create'])->name('claims.create');
    Route::post('/claims', [ClaimsController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}/edit', [ClaimsController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ClaimsController::class, 'update'])->name('claims.update');
    Route::delete('/claims/{claim}', [ClaimsController::class, 'destroy'])->name('claims.destroy');
    Route::get('/claims/export', [ClaimsController::class, 'exportCsv'])->name('claims.export');
    Route::put('/claims/{claim}/status', [ClaimsController::class, 'updateStatus'])->name('claims.update.status');
    
    // Leave routes
    Route::get('/manage-leave', [LeaveController::class, 'manageLeave'])->name('leaves.manage');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leaveRequest}', [LeaveController::class, 'show'])->name('leaves.show');
    Route::get('/leaves/{leaveRequest}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{leaveRequest}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('/leaves/{leaveRequest}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::get('/leaves/export', [LeaveController::class, 'exportCsv'])->name('leaves.export');
    Route::put('/leave-requests/{leaveRequest}/status', [LeaveController::class, 'updateStatus'])->name('leave-requests.update.status');
    
    // Test route for debugging
    Route::get('/test-status', function() {
        return response()->json(['message' => 'Test route works']);
    });
    
    // Leave Type routes
    Route::get('/leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
    Route::get('/leave-types/create', [LeaveTypeController::class, 'create'])->name('leave-types.create');
    Route::post('/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
    Route::get('/leave-types/{leave}', [LeaveTypeController::class, 'show'])->name('leave-types.show');
    Route::get('/leave-types/{leave}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit');
    Route::put('/leave-types/{leave}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
    Route::delete('/leave-types/{leave}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');
    
    // Overtime routes
    Route::get('/overtime/{attendance}', [OvertimeController::class, 'show']);
    Route::put('/overtime/{attendance}/update', [OvertimeController::class, 'updateOvertimeRequest']);
    Route::put('/overtime/{attendance}/status', [OvertimeController::class, 'updateStatus'])->name('overtime.update.status');
    
    // Overtime Request routes
    Route::get('/overtime-requests', [OvertimeRequestController::class, 'index'])->name('overtime-requests.index');
    Route::get('/overtime-requests/create', [OvertimeRequestController::class, 'create'])->name('overtime-requests.create');
    Route::post('/overtime-requests', [OvertimeRequestController::class, 'store'])->name('overtime-requests.store');
    Route::get('/overtime-requests/{overtimeRequest}', [OvertimeRequestController::class, 'show'])->name('overtime-requests.show');
    Route::get('/overtime-requests/{overtimeRequest}/edit', [OvertimeRequestController::class, 'edit'])->name('overtime-requests.edit');
    Route::put('/overtime-requests/{overtimeRequest}', [OvertimeRequestController::class, 'update'])->name('overtime-requests.update');
    Route::delete('/overtime-requests/{overtimeRequest}', [OvertimeRequestController::class, 'destroy'])->name('overtime-requests.destroy');
    Route::put('/overtime-requests/{overtimeRequest}/status', [OvertimeRequestController::class, 'updateStatus'])->name('overtime-requests.update.status');
    Route::get('/overtime-requests/export', [OvertimeRequestController::class, 'exportCsv'])->name('overtime-requests.export');
    
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
    Route::get('/shifts/{shift}', [ShiftController::class, 'show']);
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
    
    // Audit routes
    Route::get('/audit-logs', [\App\Http\Controllers\AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit-logs/create', [\App\Http\Controllers\AuditController::class, 'create'])->name('audit.create');
    Route::post('/audit-logs', [\App\Http\Controllers\AuditController::class, 'store'])->name('audit.store');
    Route::get('/audit-logs/{user}/edit', [\App\Http\Controllers\AuditController::class, 'edit'])->name('audit.edit');
    Route::put('/audit-logs/{user}', [\App\Http\Controllers\AuditController::class, 'update'])->name('audit.update');
    Route::delete('/audit-logs/{user}', [\App\Http\Controllers\AuditController::class, 'destroy'])->name('audit.destroy');
});