<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;

class TimesheetController extends Controller
{  
    /**
     * Display the timesheet record page.
     */
    public function index(Request $request)
    {
        $query = Attendance::query();
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->input('from_date'));
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->input('to_date'));
        }
        
        // Filter by employee name
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        $timesheets = $query->orderBy('date', 'desc')->orderBy('employee_name')->paginate(10);

        // Map computed total hours for each record (check-in to check-out only)
        $timesheets->getCollection()->transform(function ($attendance) {
            $totalHours = 0;
            $totalMinutes = 0;

            if ($attendance->check_in && $attendance->check_out) {
                try {
                    // DEBUG: Show raw values first
                    error_log("=== DEBUG START: {$attendance->employee_name} ===");
                    error_log("RAW check_in: " . var_export($attendance->check_in, true));
                    error_log("RAW check_out: " . var_export($attendance->check_out, true));
                    error_log("RAW date: " . var_export($attendance->date, true));
                    
                    // Simple: check-in to check-out hours only
                    $checkInTime = $attendance->check_in->format('H:i:s');
                    $checkOutTime = $attendance->check_out->format('H:i:s');
                    $dateStr = $attendance->date->format('Y-m-d');

                    // DEBUG: Show what we're working with
                    error_log("FORMATTED - Date: $dateStr, In: $checkInTime, Out: $checkOutTime");

                    $checkInDateTime = \Carbon\Carbon::parse($dateStr . ' ' . $checkInTime);
                    $checkOutDateTime = \Carbon\Carbon::parse($dateStr . ' ' . $checkOutTime);

                    // DEBUG: Show parsed datetimes
                    error_log("PARSED In: {$checkInDateTime->format('Y-m-d H:i:s')}");
                    error_log("PARSED Out: {$checkOutDateTime->format('Y-m-d H:i:s')}");
                    error_log("Is Out < In? " . ($checkOutDateTime->lt($checkInDateTime) ? 'YES' : 'NO'));

                    if ($checkOutDateTime->lt($checkInDateTime)) {
                        $checkOutDateTime->addDay();
                        error_log("AFTER ADD DAY: {$checkOutDateTime->format('Y-m-d H:i:s')}");
                    }

                    $minutes = $checkOutDateTime->diffInMinutes($checkInDateTime);
                    error_log("DIFF IN MINUTES: $minutes");
                    
                    // Remove max(0, $minutes) - let natural time difference work
                    $totalHours = intdiv($minutes, 60);
                    $totalMinutes = $minutes % 60;
                    
                    error_log("FINAL RESULT: {$totalHours}h {$totalMinutes}m");
                    error_log("=== DEBUG END ===");
                } catch (\Exception $e) {
                    error_log("DEBUG ERROR: " . $e->getMessage());
                    error_log("ERROR TRACE: " . $e->getTraceAsString());
                    $totalHours = 0;
                    $totalMinutes = 0;
                }
            }

            $attendance->total_hours = $totalHours;
            $attendance->total_minutes = $totalMinutes;

            return $attendance;
        });

        // Calculate statistics
        $totalHoursThisWeek = $this->calculateTotalHoursThisWeek();
        $pendingCount = 0; // This would come from a separate timesheet approval table
        $approvedTodayCount = 0; // This would come from approval tracking
        $overtimeHoursThisMonth = $this->calculateOvertimeHoursThisMonth();
        
        return view('admin.timesheets.timesheets-record', compact('timesheets', 'totalHoursThisWeek', 'pendingCount', 'approvedTodayCount', 'overtimeHoursThisMonth'));
    }
    
    /**
     * Search employees for timesheet filtering.
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

    public function exportRecordCsv(Request $request)
    {
        $query = Attendance::query();

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->input('to_date'));
        }

        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                    ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }

        $timesheets = $query->orderBy('date', 'desc')->orderBy('employee_name')->get();

        $csvContent = "\xEF\xBB\xBF";
        $csvContent .= "Employee ID,Employee Name,Date,Check In,Check Out,Overtime,Work Hours,Status\n";

        foreach ($timesheets as $record) {
            $dateStr = $record->date ? $record->date->format('m/d/Y') : '--';
            $checkInStr = $record->check_in ? $record->check_in->format('h:i A') : '--';
            $checkOutStr = $record->check_out ? $record->check_out->format('h:i A') : '--';

            $overtimeStr = '--';
            if ($record->overtime) {
                if ($record->overtime instanceof \Carbon\Carbon) {
                    $overtimeStr = $record->overtime->format('H:i:s');
                } else {
                    $overtimeStr = (string) $record->overtime;
                }
            }

            $workHoursStr = '--';
            if ($record->check_in && $record->check_out) {
                try {
                    $attendanceDateStr = $record->date instanceof \Carbon\Carbon
                        ? $record->date->format('Y-m-d')
                        : \Carbon\Carbon::parse($record->date)->format('Y-m-d');

                    $checkInTimeStr = $record->check_in instanceof \Carbon\Carbon
                        ? $record->check_in->format('H:i:s')
                        : \Carbon\Carbon::parse($record->check_in)->format('H:i:s');

                    $checkOutTimeStr = $record->check_out instanceof \Carbon\Carbon
                        ? $record->check_out->format('H:i:s')
                        : \Carbon\Carbon::parse($record->check_out)->format('H:i:s');

                    $checkInDateTime = \Carbon\Carbon::parse($attendanceDateStr . ' ' . $checkInTimeStr);
                    $checkOutDateTime = \Carbon\Carbon::parse($attendanceDateStr . ' ' . $checkOutTimeStr);

                    if ($checkOutDateTime->lt($checkInDateTime)) {
                        $checkOutDateTime->addDay();
                    }

                    $minutes = $checkInDateTime->diffInMinutes($checkOutDateTime);
                    $hours = floor($minutes / 60);
                    $mins = $minutes % 60;
                    $workHoursStr = sprintf('%dh %02dm', $hours, $mins);
                } catch (\Throwable $e) {
                    $workHoursStr = '--';
                }
            }

            $employeeName = $record->employee_name ? (string) $record->employee_name : '';
            $employeeName = str_replace('"', '""', $employeeName);

            $statusStr = $record->status ? ucfirst((string) $record->status) : '';
            $statusStr = str_replace('"', '""', $statusStr);

            $csvContent .= implode(',', [
                '"' . (string) $record->employee_id . '"',
                '"' . $employeeName . '"',
                '"' . $dateStr . '"',
                '"' . $checkInStr . '"',
                '"' . $checkOutStr . '"',
                '"' . str_replace('"', '""', $overtimeStr) . '"',
                '"' . $workHoursStr . '"',
                '"' . $statusStr . '"',
            ]) . "\n";
        }

        $filename = 'timesheet_record_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Calculate total hours for this week.
     */
    private function calculateTotalHoursThisWeek()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $attendances = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->get();
            
        $totalMinutes = 0;
        foreach ($attendances as $attendance) {
            try {
                $checkIn = \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_in->format('H:i:s'));
                $checkOut = \Carbon\Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->check_out->format('H:i:s'));
                
                // If check-out is earlier than check-in, assume it's next day
                if ($checkOut->lt($checkIn)) {
                    $checkOut->addDay();
                }
                
                $minutes = $checkOut->diffInMinutes($checkIn);
                
                // Only add positive minutes
                if ($minutes > 0) {
                    $totalMinutes += $minutes;
                }
            } catch (\Exception $e) {
                // Skip invalid records
                continue;
            }
        }
        
        return number_format(max(0, $totalMinutes / 60), 1);
    }
    
    /**
     * Calculate overtime hours for this month.
     */
    private function calculateOvertimeHoursThisMonth()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $attendances = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->whereNotNull('overtime')
            ->get();
            
        $totalMinutes = 0;
        foreach ($attendances as $attendance) {
            if ($attendance->overtime) {
                $overtimeParts = explode(':', $attendance->overtime->format('H:i'));
                $totalMinutes += ($overtimeParts[0] * 60) + $overtimeParts[1];
            }
        }
        
        return number_format($totalMinutes / 60, 1);
    }
    
    /**
     * Display the timesheet report page.
     */
    public function report()
    {
        return view('admin.timesheets.timesheets-report');
    }
    
    /**
     * Search employees for report.
     */
    public function searchEmployeesReport(Request $request)
    {
        $search = $request->get('search');
        
        $employees = Employee::where('employee_name', 'like', "%{$search}%")
            ->orWhere('id', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'employee_name', 'position', 'department']);
            
        return response()->json($employees);
    }
    
    /**
     * Get employee attendance report data.
     */
    public function getEmployeeReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ]);
        
        $employeeId = $request->input('employee_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        
        // Get employee information
        $employee = Employee::where('id', $employeeId)
            ->orWhere('employee_name', 'like', "%{$employeeId}%")
            ->first();
            
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
        
        // Get attendance records
        $attendances = Attendance::where('employee_name', $employee->employee_name)
            ->whereBetween('date', [$fromDate, $toDate])
            ->orderBy('date')
            ->get();
        
        // Debug logging
        \Log::info('Employee Report Debug', [
            'employee_name' => $employee->employee_name,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'total_attendance_records' => $attendances->count()
        ]);
        
        // Calculate summary
        $totalWorkHours = 0;
        $totalOvertimeHours = 0;
        $daysPresent = 0;
        $attendanceData = [];
        
        foreach ($attendances as $attendance) {
            $dayWorkHours = 0;
            $dayOvertimeHours = 0;
            
            \Log::info('Processing attendance record', [
                'date' => $attendance->date,
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'overtime' => $attendance->overtime
            ]);
            
            if ($attendance->check_in && $attendance->check_out) {
                try {
                    $attendanceDateStr = $attendance->date instanceof \Carbon\Carbon
                        ? $attendance->date->format('Y-m-d')
                        : \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');

                    $checkInTimeStr = $attendance->check_in instanceof \Carbon\Carbon
                        ? $attendance->check_in->format('H:i:s')
                        : \Carbon\Carbon::parse($attendance->check_in)->format('H:i:s');

                    $checkOutTimeStr = $attendance->check_out instanceof \Carbon\Carbon
                        ? $attendance->check_out->format('H:i:s')
                        : \Carbon\Carbon::parse($attendance->check_out)->format('H:i:s');

                    $checkInDateTime = \Carbon\Carbon::parse($attendanceDateStr . ' ' . $checkInTimeStr);
                    $checkOutDateTime = \Carbon\Carbon::parse($attendanceDateStr . ' ' . $checkOutTimeStr);
                    
                    // Handle overnight shifts
                    if ($checkOutDateTime->lt($checkInDateTime)) {
                        $checkOutDateTime->addDay();
                    }
                    
                    // Calculate actual work hours from check-in to check-out
                    $workMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);
                    $dayWorkHours = $workMinutes / 60;
                    
                    \Log::info('Work hours calculation', [
                        'date' => $attendance->date,
                        'check_in_datetime' => $checkInDateTime,
                        'check_out_datetime' => $checkOutDateTime,
                        'check_in_ts' => $checkInDateTime->timestamp,
                        'check_out_ts' => $checkOutDateTime->timestamp,
                        'work_minutes' => $workMinutes,
                        'day_work_hours' => $dayWorkHours
                    ]);
                    
                    if ($dayWorkHours > 0) {
                        $daysPresent++;
                    }
                    
                    // Calculate overtime from the overtime field
                    if ($attendance->overtime) {
                        $overtimeValue = $attendance->overtime;
                        
                        // If overtime is a datetime, extract just the time part
                        if ($overtimeValue instanceof \Carbon\Carbon) {
                            // Convert datetime to time string (HH:MM:SS)
                            $overtimeTimeStr = $overtimeValue->format('H:i:s');
                            $overtimeParts = explode(':', $overtimeTimeStr);
                            $overtimeHours = (int) ($overtimeParts[0] ?? 0);
                            $overtimeMinutes = (int) ($overtimeParts[1] ?? 0);
                        } elseif (is_string($overtimeValue) && strpos($overtimeValue, ':') !== false) {
                            // Already a time string like "01:30:00"
                            $overtimeParts = explode(':', $overtimeValue);
                            $overtimeHours = (int) ($overtimeParts[0] ?? 0);
                            $overtimeMinutes = (int) ($overtimeParts[1] ?? 0);
                        } else {
                            $overtimeHours = 0;
                            $overtimeMinutes = 0;
                        }
                        
                        $dayOvertimeHours = ($overtimeHours * 60 + $overtimeMinutes) / 60;
                        
                        \Log::info('Overtime calculation', [
                            'date' => $attendance->date,
                            'overtime_raw' => $overtimeValue,
                            'overtime_hours' => $overtimeHours,
                            'overtime_minutes' => $overtimeMinutes,
                            'day_overtime_hours' => $dayOvertimeHours
                        ]);
                    }
                    
                    $totalWorkHours += $dayWorkHours;
                    $totalOvertimeHours += $dayOvertimeHours;
                    
                } catch (\Throwable $e) {
                    \Log::error('Calculation error', [
                        'employee' => $attendance->employee_name,
                        'date' => $attendance->date,
                        'check_in' => $attendance->check_in,
                        'check_out' => $attendance->check_out,
                        'error' => $e->getMessage()
                    ]);
                    $dayWorkHours = 0;
                    $dayOvertimeHours = 0;
                }
            }
            
            $attendanceData[] = [
                'date' => $attendance->date->format('M d, Y'),
                'day' => $attendance->date->format('D'),
                'check_in' => $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '--',
                'check_out' => $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '--',
                'overtime' => $attendance->overtime ? $attendance->overtime : '--',
                'total_hours' => $dayWorkHours > 0 ? number_format($dayWorkHours, 1) . 'h' : '--',
                'status' => $attendance->status ?? 'present'
            ];
        }
        
        $avgHours = $daysPresent > 0 ? $totalWorkHours / $daysPresent : 0;
        
        // Debug logging for final results
        \Log::info('Final Calculation Results', [
            'days_present' => $daysPresent,
            'total_work_hours' => $totalWorkHours,
            'total_overtime_hours' => $totalOvertimeHours,
            'avg_hours' => $avgHours
        ]);
        
        return response()->json([
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->employee_name,
                'department' => $employee->department ?? 'N/A',
                'position' => $employee->position ?? 'N/A'
            ],
            'summary' => [
                'days_present' => $daysPresent,
                'total_hours' => number_format($totalWorkHours, 1),
                'overtime_hours' => number_format($totalOvertimeHours, 1),
                'avg_hours' => number_format($avgHours, 1)
            ],
            'attendance' => $attendanceData
        ]);
    }
}
 