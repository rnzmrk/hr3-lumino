<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class OvertimeController extends Controller
{
    /**
     * Display the overtime management page.
     */
    public function index()
    {
        // Get ALL attendance records
        $overtimeRequests = Attendance::latest()->get();
        
        // Calculate stats based on overtime presence
        $pendingCount = $overtimeRequests->where('status', 'pending')->count();
        $approvedCount = $overtimeRequests->where('status', 'approved')->count();
        $rejectedCount = $overtimeRequests->where('status', 'rejected')->count();
        $totalCount = $overtimeRequests->count();
        
        // Get today's counts
        $today = now()->format('Y-m-d');
        $pendingToday = $overtimeRequests->where('status', 'pending')->where('created_at', '>=', $today)->count();
        $approvedToday = $overtimeRequests->where('status', 'approved')->where('created_at', '>=', $today)->count();
        $rejectedToday = $overtimeRequests->where('status', 'rejected')->where('created_at', '>=', $today)->count();
        
        $stats = [
            'pending' => [
                'count' => $pendingCount,
                'new_today' => $pendingToday
            ],
            'approved' => [
                'count' => $approvedCount,
                'new_today' => $approvedToday
            ],
            'rejected' => [
                'count' => $rejectedCount,
                'new_today' => $rejectedToday
            ],
            'total' => [
                'count' => $totalCount,
                'new_today' => $pendingToday + $approvedToday + $rejectedToday
            ]
        ];
        
        return view('admin.schedule.overtime', compact('overtimeRequests', 'stats'));
    }

    /**
     * Show specific overtime request.
     */
    public function show(Attendance $attendance)
    {
        return response()->json([
            'success' => true,
            'overtime_request' => $attendance
        ]);
    }

    /**
     * Update overtime request.
     */
    public function updateOvertimeRequest(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in' => 'nullable|string',
            'check_out' => 'nullable|string',
            'overtime' => 'nullable|string', // Will store time like "2:30" for 2 hours 30 minutes
            'status' => 'required|string|in:present,late,awol,pending,approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $attendance->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Overtime request updated successfully",
            'attendance' => $attendance
        ]);
    }

    /**
     * Update overtime status.
     */
    public function updateStatus(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $attendance->update($validated);

        return response()->json([
            'success' => true,
            'message' => "Overtime request {$request->status} successfully",
            'attendance' => $attendance
        ]);
    }

    /**
     * Get overtime requests for API.
     */
    public function getOvertimeRequests(Request $request)
    {
        // Get ALL attendance records
        $query = Attendance::query();

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by employee name
        if ($request->has('search') && $request->search) {
            $query->where('employee_name', 'like', '%' . $request->search . '%');
        }

        $overtimeRequests = $query->latest()->get();

        return response()->json([
            'success' => true,
            'overtime_requests' => $overtimeRequests
        ]);
    }

    /**
     * Export overtime requests to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Attendance::query();
        
        // Apply same filters as getOvertimeRequests method
        if ($request->has('date') && $request->date) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search) {
            $query->where('employee_name', 'like', '%' . $request->search . '%');
        }
        
        $overtimeRequests = $query->latest()->get();
        
        // Create CSV content
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "Employee Name,Date,Check In,Check Out,Overtime,Status,Created At\n";
        
        foreach ($overtimeRequests as $request) {
            // Format check-in and check-out times properly
            $checkInTime = '';
            $checkOutTime = '';
            
            if ($request->check_in) {
                try {
                    if ($request->check_in instanceof \Carbon\Carbon) {
                        $checkInTime = $request->check_in->format('h:i A');
                    } else {
                        $checkInString = (string) $request->check_in;
                        if (strpos($checkInString, ' ') !== false) {
                            if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkInString)) {
                                $parts = explode(' ', $checkInString);
                                $checkInTime = \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2])->format('h:i A');
                            } else {
                                $checkInTime = \Carbon\Carbon::parse($checkInString)->format('h:i A');
                            }
                        } else {
                            $checkInTime = \Carbon\Carbon::parse($request->date . ' ' . $checkInString)->format('h:i A');
                        }
                    }
                } catch (\Exception $e) {
                    $checkInTime = '--';
                }
            }
            
            if ($request->check_out) {
                try {
                    if ($request->check_out instanceof \Carbon\Carbon) {
                        $checkOutTime = $request->check_out->format('h:i A');
                    } else {
                        $checkOutString = (string) $request->check_out;
                        if (strpos($checkOutString, ' ') !== false) {
                            if (preg_match('/\d{4}-\d{2}-\d{2}.*\d{4}-\d{2}-\d{2}/', $checkOutString)) {
                                $parts = explode(' ', $checkOutString);
                                $checkOutTime = \Carbon\Carbon::parse($parts[0] . ' ' . $parts[1] . ' ' . $parts[2])->format('h:i A');
                            } else {
                                $checkOutTime = \Carbon\Carbon::parse($checkOutString)->format('h:i A');
                            }
                        } else {
                            $checkOutTime = \Carbon\Carbon::parse($request->date . ' ' . $checkOutString)->format('h:i A');
                        }
                    }
                } catch (\Exception $e) {
                    $checkOutTime = '--';
                }
            }
            
            // Format overtime properly
            $overtimeFormatted = '';
            if ($request->overtime) {
                try {
                    if ($request->overtime instanceof \Carbon\Carbon) {
                        $overtimeFormatted = $request->overtime->format('H:i');
                    } else {
                        $overtimeString = (string) $request->overtime;
                        // If it's just a number, format as hours
                        if (is_numeric($overtimeString)) {
                            $hours = abs((int)$overtimeString);
                            $overtimeFormatted = sprintf('%02d:00', $hours);
                        } else {
                            $overtimeFormatted = $overtimeString;
                        }
                    }
                } catch (\Exception $e) {
                    $overtimeFormatted = '--';
                }
            } else {
                $overtimeFormatted = '--';
            }
            
            $csvContent .= implode(',', [
                '"' . str_replace('"', '""', $request->employee_name ?? '') . '"',
                '"' . ($request->date ? $request->date->format('m/d/Y') : '') . '"',
                '"' . $checkInTime . '"',
                '"' . $checkOutTime . '"',
                '"' . $overtimeFormatted . '"',
                '"' . ucfirst($request->status ?? '') . '"',
                '"' . ($request->created_at ? $request->created_at->format('m/d/Y h:i A') : '') . '"'
            ]) . "\n";
        }
        
        // Generate filename with current date
        $filename = 'overtime_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        // Return CSV download
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
} 
