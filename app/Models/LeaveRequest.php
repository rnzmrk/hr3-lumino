<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_name',
        'position',
        'leave_type',
        'reason',
        'leave_dates',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'leave_dates' => 'array',
    ];

    // Get leave dates as formatted array
    public function getFormattedDatesAttribute()
    {
        if (!$this->leave_dates) {
            return [];
        }
        
        return collect($this->leave_dates)->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d, Y');
        })->toArray();
    }

    // Get leave type label
    public function getLeaveTypeLabelAttribute()
    {
        $labels = [
            'sick' => 'Sick Leave',
            'vacation' => 'Vacation',
            'personal' => 'Personal',
            'maternity' => 'Maternity',
            'emergency' => 'Emergency'
        ];
        
        return $labels[$this->leave_type] ?? ucfirst($this->leave_type);
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];
        
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-amber-100 text-amber-800',
            'approved' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800'
        ];
        
        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Get leave type badge class
    public function getLeaveTypeBadgeClassAttribute()
    {
        $classes = [
            'sick' => 'bg-red-50 text-red-700 border border-red-200',
            'vacation' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'personal' => 'bg-purple-50 text-purple-700 border border-purple-200',
            'maternity' => 'bg-pink-50 text-pink-700 border border-pink-200',
            'emergency' => 'bg-orange-50 text-orange-700 border border-orange-200'
        ];
        
        return $classes[$this->leave_type] ?? 'bg-gray-50 text-gray-700 border border-gray-200';
    }
}
