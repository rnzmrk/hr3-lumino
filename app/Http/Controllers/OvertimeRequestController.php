<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRequest;

class OvertimeRequestController extends Controller
{
    /**
     * Display a listing of overtime requests.
     */
    public function index(Request $request)
    {
        $query = OvertimeRequest::query();
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Date filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }
        
        // Pagination
        $overtimeRequests = $query->latest()->paginate(10);
        
        // Calculate stats
        $pendingCount = OvertimeRequest::where('status', 'pending')->count();
        $approvedCount = OvertimeRequest::where('status', 'approved')->count();
        $rejectedCount = OvertimeRequest::where('status', 'rejected')->count();
        $totalCount = OvertimeRequest::count();
        
        $stats = [
            'pending' => ['count' => $pendingCount],
            'approved' => ['count' => $approvedCount],
            'rejected' => ['count' => $rejectedCount],
            'total' => ['count' => $totalCount]
        ];
        
        return view('admin.overtime-requests.index', compact('overtimeRequests', 'stats'));
    }

    /**
     * Show the form for creating a new overtime request.
     */
    public function create()
    {
        return view('admin.overtime-requests.create');
    }

    /**
     * Store a newly created overtime request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:12'
        ]);

        $overtimeRequest = OvertimeRequest::create([
            'employee_name' => $request->employee_name,
            'reason' => $request->reason,
            'date' => $request->date,
            'hours' => $request->hours,
            'status' => 'pending'
        ]);

        return redirect()->route('overtime-requests.index')
            ->with('success', "✅ Overtime request for '{$overtimeRequest->employee_name}' created successfully!");
    }

    /**
     * Display the specified overtime request.
     */
    public function show(OvertimeRequest $overtimeRequest)
    {
        return view('admin.overtime-requests.show', compact('overtimeRequest'));
    }

    /**
     * Show the form for editing the specified overtime request.
     */
    public function edit(OvertimeRequest $overtimeRequest)
    {
        return view('admin.overtime-requests.edit', compact('overtimeRequest'));
    }

    /**
     * Update the specified overtime request in storage.
     */
    public function update(Request $request, OvertimeRequest $overtimeRequest)
    {
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'reason' => 'required|string|max:1000',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:12',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $overtimeRequest->update([
            'employee_name' => $request->employee_name,
            'reason' => $request->reason,
            'date' => $request->date,
            'hours' => $request->hours,
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('overtime-requests.index')
            ->with('success', "✅ Overtime request updated successfully!");
    }

    /**
     * Remove the specified overtime request from storage.
     */
    public function destroy(OvertimeRequest $overtimeRequest)
    {
        $overtimeRequest->delete();

        return redirect()->route('overtime-requests.index')
            ->with('success', "🗑️ Overtime request deleted successfully!");
    }

    /**
     * Update overtime request status.
     */
    public function updateStatus(Request $request, OvertimeRequest $overtimeRequest)
    {
        try {
            $request->validate([
                'status' => 'required|in:approved,rejected',
                'admin_notes' => 'nullable|string|max:500'
            ]);

            $oldStatus = $overtimeRequest->status;
            $overtimeRequest->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ]);

            // Log the audit activity
            \App\Services\AuditLogService::log(
                $request->status,
                'App\Models\OvertimeRequest',
                $overtimeRequest->id,
                "{$request->status} overtime request for {$overtimeRequest->employee_name} - {$overtimeRequest->overtime_hours} hours",
                ['status' => $oldStatus, 'admin_notes' => $overtimeRequest->getOriginal('admin_notes')],
                ['status' => $request->status, 'admin_notes' => $request->admin_notes]
            );

            return response()->json([
                'success' => true,
                'message' => "Overtime request {$request->status} successfully",
                'overtime_request' => $overtimeRequest
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export overtime requests to CSV.
     */
    public function exportCsv(Request $request)
    {
        // Get data with filters
        $query = OvertimeRequest::query();
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('employee_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }
        
        $overtimeRequests = $query->latest()->get();
        
        // Create CSV data array
        $csvData = [];
        
        // Add header
        $csvData[] = [
            'Request ID',
            'Employee Name', 
            'Reason',
            'Date',
            'Hours',
            'Status',
            'Admin Notes',
            'Created At'
        ];
        
        // Add data rows
        foreach ($overtimeRequests as $request) {
            $csvData[] = [
                $request->id,
                $request->employee_name,
                $request->reason,
                $request->date->format('Y-m-d'),
                number_format($request->hours, 2),
                ucfirst($request->status),
                $request->admin_notes ?? '',
                $request->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        // Return JSON data for JavaScript processing
        return response()->json([
            'success' => true,
            'data' => $csvData,
            'filename' => 'overtime_requests_' . date('Y-m-d_H-i-s') . '.csv'
        ]);
    }
}
