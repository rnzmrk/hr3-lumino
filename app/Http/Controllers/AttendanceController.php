<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceController extends Controller
{
    /**
     * Display the attendance management page.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Filter by employee name
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        $attendance = $query->orderBy('date', 'desc')->orderBy('employee_name')->paginate(10);
        
        // Get statistics based on today's date (always)
        $todayDate = now()->format('Y-m-d');
        $presentCount = Attendance::whereDate('date', $todayDate)->where('status', 'present')->count();
        $absentCount = Attendance::whereDate('date', $todayDate)->where('status', 'absent')->count();
        $lateCount = Attendance::whereDate('date', $todayDate)->where('status', 'late')->count();
        $onLeaveCount = Attendance::whereDate('date', $todayDate)->where('status', 'leave')->count();
        
        // Create stats array for the view
        $stats = [
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
            'leave' => $onLeaveCount
        ];
        
        return view('admin.attendance.attendance', compact('attendance', 'stats'));
    }
    
    /**
     * Search employees for attendance marking.
     */
    public function searchEmployees(Request $request)
    {
        $search = $request->get('search');
        
        $employees = Employee::where('employee_name', 'like', "%{$search}%")
            ->orWhere('id', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'employee_name', 'position', 'department']);
            
        return response()->json($employees);
    }
    
    /**
     * Store new attendance record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'employee_name' => 'required|string|max:255',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'overtime' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:present,late,awol,leave,absent',
        ]);

        // Handle both old and new field names
        $checkInTime = $validated['time_in'] ?? $validated['check_in'] ?? null;
        $checkOutTime = $validated['time_out'] ?? $validated['check_out'] ?? null;

        // Check if attendance already exists for this employee on this date
        $existing = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $validated['date'])
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already recorded for this employee on this date.'
            ], 422);
        }

        $attendance = Attendance::create([
            'employee_id' => $validated['employee_id'],
            'employee_name' => $validated['employee_name'],
            'date' => $validated['date'],
            'check_in' => $checkInTime,
            'check_out' => $checkOutTime,
            'overtime' => $validated['overtime'],
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully!',
            'attendance' => $attendance
        ]);
    }
    
    /**
     * Update attendance record.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'employee_name' => 'required|string|max:255',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'overtime' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,awol,leave,absent',
        ]);
        
        // Handle both old and new field names
        $checkInTime = $request->input('time_in') ?? $request->input('check_in');
        $checkOutTime = $request->input('time_out') ?? $request->input('check_out');
        
        $attendance->update([
            'employee_id' => $request->input('employee_id'),
            'employee_name' => $request->input('employee_name'),
            'date' => $request->input('date'),
            'check_in' => $checkInTime,
            'check_out' => $checkOutTime,
            'overtime' => $request->input('overtime'),
            'status' => $request->input('status'),
        ]);
        
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record updated successfully.');
    }
    
    /**
     * Export attendance data to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Attendance::query();
        
        // Apply same filters as index method
        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        $attendance = $query->orderBy('date', 'desc')->orderBy('employee_name')->get();
        
        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "Employee ID,Employee Name,Date,Check In,Check Out,Overtime,Status\n";
        
        foreach ($attendance as $record) {
            $csvContent .= implode(',', [
                $record->employee_id,
                '"' . str_replace('"', '""', $record->employee_name) . '"', // Escape quotes
                '"' . $record->date->format('m/d/Y') . '"', // Date as text to avoid Excel formatting issues
                '"' . ($record->check_in ? $record->check_in->format('h:i A') : '--') . '"', // Time as text
                '"' . ($record->check_out ? $record->check_out->format('h:i A') : '--') . '"', // Time as text
                '"' . ($record->overtime ? $record->overtime->format('h:i A') : '--') . '"', // Time as text
                '"' . ucfirst($record->status) . '"' // Status as text
            ]) . "\n";
        }
        
        // Generate filename with current date
        $filename = 'attendance_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Remove attendance record.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * Record time in for attendance.
     */
    public function timeIn(Attendance $attendance)
    {
        try {
            // Get employee's shift schedule for default time
            $dayOfWeek = $attendance->date->format('l'); // Full day name (Monday, Tuesday, etc.)
            $shift = \App\Models\Shift::where('employee_id', $attendance->employee_id)
                ->where('days', 'like', '%' . $dayOfWeek . '%')
                ->first();

            $defaultTime = now();
            if ($shift) {
                // Use shift schedule start time as default
                $scheduleStart = \Carbon\Carbon::parse($shift->schedule_start);
                $defaultTime = $attendance->date->copy()->setTime(
                    $scheduleStart->hour,
                    $scheduleStart->minute,
                    $scheduleStart->second
                );
                
                // Log for debugging (you can remove this in production)
                \Log::info('Time In - Shift found', [
                    'attendance_id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'day_of_week' => $dayOfWeek,
                    'shift_schedule_start' => $shift->schedule_start,
                    'default_time' => $defaultTime->format('Y-m-d H:i:s')
                ]);
            } else {
                // Try to find any shift for this employee as fallback
                $anyShift = \App\Models\Shift::where('employee_id', $attendance->employee_id)->first();
                
                if ($anyShift) {
                    // Use the employee's regular shift start time as fallback
                    $scheduleStart = \Carbon\Carbon::parse($anyShift->schedule_start);
                    $defaultTime = $attendance->date->copy()->setTime(
                        $scheduleStart->hour,
                        $scheduleStart->minute,
                        $scheduleStart->second
                    );
                    
                    \Log::info('Time In - Using fallback shift', [
                        'attendance_id' => $attendance->id,
                        'employee_id' => $attendance->employee_id,
                        'day_of_week' => $dayOfWeek,
                        'fallback_shift_start' => $anyShift->schedule_start,
                        'default_time' => $defaultTime->format('Y-m-d H:i:s')
                    ]);
                } else {
                    \Log::warning('Time In - No shift found at all', [
                        'attendance_id' => $attendance->id,
                        'employee_id' => $attendance->employee_id,
                        'day_of_week' => $dayOfWeek
                    ]);
                }
            }

            $attendance->update([
                'check_in' => $defaultTime,
                'status' => 'present'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time In recorded successfully',
                'time' => $defaultTime->format('h:i A')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record Time In: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Record time out for attendance.
     */
    public function timeOut(Attendance $attendance)
    {
        try {
            // Get employee's shift schedule for default time
            $dayOfWeek = $attendance->date->format('l'); // Full day name (Monday, Tuesday, etc.)
            $shift = \App\Models\Shift::where('employee_id', $attendance->employee_id)
                ->where('days', 'like', '%' . $dayOfWeek . '%')
                ->first();

            $defaultTime = now();
            
            if ($shift) {
                // Use shift schedule end time as default
                $scheduleEnd = \Carbon\Carbon::parse($shift->schedule_end);
                $defaultTime = $attendance->date->copy()->setTime(
                    $scheduleEnd->hour,
                    $scheduleEnd->minute,
                    $scheduleEnd->second
                );
                
                // Log for debugging (you can remove this in production)
                \Log::info('Time Out - Shift found', [
                    'attendance_id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'day_of_week' => $dayOfWeek,
                    'shift_schedule_end' => $shift->schedule_end,
                    'default_time' => $defaultTime->format('Y-m-d H:i:s')
                ]);
            } else {
                // Try to find any shift for this employee as fallback
                $anyShift = \App\Models\Shift::where('employee_id', $attendance->employee_id)->first();
                
                if ($anyShift) {
                    // Use the employee's regular shift end time as fallback
                    $scheduleEnd = \Carbon\Carbon::parse($anyShift->schedule_end);
                    $defaultTime = $attendance->date->copy()->setTime(
                        $scheduleEnd->hour,
                        $scheduleEnd->minute,
                        $scheduleEnd->second
                    );
                    
                    \Log::info('Time Out - Using fallback shift', [
                        'attendance_id' => $attendance->id,
                        'employee_id' => $attendance->employee_id,
                        'day_of_week' => $dayOfWeek,
                        'fallback_shift_end' => $anyShift->schedule_end,
                        'default_time' => $defaultTime->format('Y-m-d H:i:s')
                    ]);
                } else {
                    \Log::warning('Time Out - No shift found at all', [
                        'attendance_id' => $attendance->id,
                        'employee_id' => $attendance->employee_id,
                        'day_of_week' => $dayOfWeek
                    ]);
                }
            }

            $attendance->update([
                'check_out' => $defaultTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Time Out recorded successfully',
                'time' => $defaultTime->format('h:i A')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record Time Out: ' . $e->getMessage()
            ], 500);
        }
    }
}
