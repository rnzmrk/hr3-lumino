<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;

class LeaveController extends Controller
{
    /**
     * Display the manage leave page.
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::latest()->get();
        
        // Calculate stats
        $pendingCount = $leaveRequests->where('status', 'pending')->count();
        $approvedCount = $leaveRequests->where('status', 'approved')->count();
        $rejectedCount = $leaveRequests->where('status', 'rejected')->count();
        $totalCount = $leaveRequests->count();
        
        // Get today's counts
        $today = now()->format('Y-m-d');
        $pendingToday = $leaveRequests->where('status', 'pending')->where('created_at', '>=', $today)->count();
        $approvedToday = $leaveRequests->where('status', 'approved')->where('created_at', '>=', $today)->count();
        $rejectedToday = $leaveRequests->where('status', 'rejected')->where('created_at', '>=', $today)->count();
        
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
        
        return view('admin.leaves.manage-leave', compact('leaveRequests', 'stats'));
    }

    /**
     * Display the manage leave page (alias for index).
     */
    public function manageLeave()
    {
        return $this->index();
    }

    /**
     * Store a new leave request.
     */
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('Leave request submission received', [
            'all_data' => $request->all(),
            'leave_dates' => $request->leave_dates,
            'employee_name' => $request->employee_name,
            'position' => $request->position,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason
        ]);

        $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'leave_type' => 'required|in:sick,vacation,personal,maternity,emergency',
            'reason' => 'required|string|max:1000',
            'leave_dates' => 'required|array|min:1',
            'leave_dates.*' => 'required|date'
        ]);

        $leaveRequest = LeaveRequest::create([
            'employee_name' => $request->employee_name,
            'position' => $request->position,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'leave_dates' => $request->leave_dates,
            'status' => 'pending'
        ]);

        \Log::info('Leave request created successfully', ['leave_request' => $leaveRequest]);

        return response()->json([
            'success' => true,
            'message' => 'Leave request submitted successfully',
            'leave_request' => $leaveRequest
        ]);
    }

    /**
     * Update leave request status.
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $leaveRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => "Leave request {$request->status} successfully",
            'leave_request' => $leaveRequest
        ]);
    }

    /**
     * Get leave request details.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return response()->json([
            'success' => true,
            'leave_request' => $leaveRequest
        ]);
    }

    /**
     * Delete leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave request deleted successfully'
        ]);
    }

    /**
     * Get leave requests for API.
     */
    public function getLeaveRequests(Request $request)
    {
        $query = LeaveRequest::query();

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->has('leave_type') && $request->leave_type) {
            $query->where('leave_type', $request->leave_type);
        }

        // Search by employee name
        if ($request->has('search') && $request->search) {
            $query->where('employee_name', 'like', '%' . $request->search . '%');
        }

        $leaveRequests = $query->latest()->get();

        return response()->json([
            'success' => true,
            'leave_requests' => $leaveRequests
        ]);
    }
}
