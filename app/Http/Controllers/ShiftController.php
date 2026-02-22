<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Show the schedule management page.
     */
    public function showSchedule(Request $request)
    {
        $query = Shift::query();
        
        // Filter by employee search
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        // Filter by shift type
        if ($request->filled('shift_type')) {
            $query->where('shift_type', $request->input('shift_type'));
        }
        
        // Filter by day
        if ($request->filled('day_filter')) {
            $dayFilter = $request->input('day_filter');
            $query->where('days', 'like', "%{$dayFilter}%");
        }
        
        $shifts = $query->orderBy('employee_name')->paginate(5);
        $employees = Employee::orderBy('employee_name')->get();
        
        // Get shift counts by type
        $morningShiftCount = Shift::where('shift_type', 'Morning Shift')->count();
        $afternoonShiftCount = Shift::where('shift_type', 'Afternoon Shift')->count();
        $nightShiftCount = Shift::where('shift_type', 'Night Shift')->count();
        $totalShiftsCount = Shift::count();
        
        return view('admin.schedule.schedule-management', compact('employees', 'shifts', 'morningShiftCount', 'afternoonShiftCount', 'nightShiftCount', 'totalShiftsCount'));
    }

    /**
     * Store a newly created shift in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'shift_type' => 'required|in:Morning Shift,Afternoon Shift,Night Shift',
            'schedule_start' => 'required|date_format:H:i',
            'schedule_end' => 'required|date_format:H:i',
            'days' => 'required|array|min:1',
            'days.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
        ]);

        try {
            // Get employee name from employees table
            $employee = Employee::find($validated['employee_id']);
            if (!$employee) {
                return back()->with('error', 'Employee not found');
            }

            // Prepare shift data with employee name from database
            $shiftData = [
                'employee_id' => $validated['employee_id'],
                'employee_name' => $employee->employee_name,
                'shift_type' => $validated['shift_type'],
                'schedule_start' => $validated['schedule_start'],
                'schedule_end' => $validated['schedule_end'],
                'days' => implode(', ', $validated['days']),
            ];

            $shift = Shift::create($shiftData);

            return back()->with('success', 'Shift added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding shift: ' . $e->getMessage());
        }
    }

    /**
     * Get all shifts for the schedule management table.
     */
    public function index()
    {
        $shifts = Shift::orderBy('employee_name')->get();
        return response()->json($shifts);
    }

    /**
     * Search employees for the shift form.
     */
    public function searchEmployees(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $employees = Employee::where('employee_name', 'like', "%{$query}%")
            ->orWhere('id', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'employee_name']);
                
        return response()->json($employees);
    }

    /**
     * Show the form for editing a specific shift.
     */
    public function edit(Shift $shift)
    {
        try {
            $employees = Employee::orderBy('employee_name')->get();
            $shiftDays = explode(', ', $shift->days);
            
            return view('admin.schedule.edit-shift', compact('shift', 'employees', 'shiftDays'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading shift data: ' . $e->getMessage());
        }
    }

    /**
     * Update a shift.
     */
    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'shift_type' => 'required|in:Morning Shift,Afternoon Shift,Night Shift',
            'schedule_start' => 'required|date_format:H:i',
            'schedule_end' => 'required|date_format:H:i',
            'days' => 'required|array|min:1',
            'days.*' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
        ]);

        try {
            // Prepare shift data
            $shiftData = [
                'shift_type' => $validated['shift_type'],
                'schedule_start' => $validated['schedule_start'],
                'schedule_end' => $validated['schedule_end'],
                'days' => implode(', ', $validated['days']),
            ];

            $shift->update($shiftData);

            return redirect()->route('schedule.management')->with('success', 'Shift updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating shift: ' . $e->getMessage());
        }
    }

    /**
     * Delete a shift.
     */
    public function destroy(Shift $shift)
    {
        try {
            $shift->delete();

            return back()->with('success', 'Shift deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting shift: ' . $e->getMessage());
        }
    }

    /**
     * Export shifts to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Shift::query();
        
        // Apply same filters as showSchedule method
        if ($request->filled('employee_search')) {
            $searchTerm = $request->input('employee_search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('shift_type')) {
            $query->where('shift_type', $request->input('shift_type'));
        }
        
        if ($request->filled('day_filter')) {
            $dayFilter = $request->input('day_filter');
            $query->where('days', 'like', "%{$dayFilter}%");
        }
        
        $shifts = $query->orderBy('employee_name')->get();
        
        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "Employee ID,Employee Name,Shift Type,Schedule Start,Schedule End,Days\n";
        
        foreach ($shifts as $shift) {
            $csvContent .= implode(',', [
                $shift->employee_id,
                '"' . str_replace('"', '""', $shift->employee_name) . '"',
                '"' . $shift->shift_type . '"',
                '"' . $shift->schedule_start . '"',
                '"' . $shift->schedule_end . '"',
                '"' . $shift->days . '"'
            ]) . "\n";
        }
        
        // Generate filename with current date
        $filename = 'schedule_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
