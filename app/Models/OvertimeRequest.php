<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeRequest extends Model
{
    protected $fillable = [
        'employee_name',
        'reason',
        'date',
        'hours',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2'
    ];

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
}
