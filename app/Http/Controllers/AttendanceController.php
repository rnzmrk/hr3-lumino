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
            'overtime' => 'nullable|date_format:H:i',
            'status' => 'nullable|in:present,late,awol,leave,absent',
        ]);

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

        $attendance = Attendance::create($validated);

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
            'overtime' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,late,awol,leave,absent',
        ]);
        
        $attendance->update($request->all());
        
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
}
