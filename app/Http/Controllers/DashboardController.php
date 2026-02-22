<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Claim;
use App\Models\Shift;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's date in Philippines timezone
        $today = Carbon::now('Asia/Manila')->format('Y-m-d');
        
        // Get Present Today count
        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'present')
            ->distinct('employee_name')
            ->count();
        
        // Get Leave Pending count
        $leavePending = LeaveRequest::where('status', 'pending')->count();
        
        // Get Claims Pending count
        $claimsPending = Claim::where('status', 'pending')->count();
        
        // Get Total Shifts count (all shifts)
        $totalShifts = Shift::count();
        
        // Get total employees for calculations
        $totalEmployees = Employee::count();
        
        return view('admin.dashboard', compact(
            'presentToday',
            'leavePending', 
            'claimsPending',
            'totalShifts',
            'totalEmployees'
        ));
    }
}
