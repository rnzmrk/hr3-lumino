<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'employee_id',
        'shift_type',
        'schedule_start',
        'schedule_end',
        'days',
    ];

    protected $casts = [
        'schedule_start' => 'datetime:H:i',
        'schedule_end' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get shift types enum values.
     */
    public static function getShiftTypes(): array
    {
        return [
            'Morning Shift',
            'Afternoon Shift',
            'Night Shift',
        ];
    }

    /**
     * Scope to filter by shift type.
     */
    public function scopeByShiftType($query, $shiftType)
    {
        return $query->where('shift_type', $shiftType);
    }

    /**
     * Scope to filter by employee.
     */
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Get formatted schedule time.
     */
    public function getFormattedScheduleAttribute(): string
    {
        return $this->schedule_start->format('h:i A') . ' - ' . $this->schedule_end->format('h:i A');
    }

    /**
     * Get shift color based on type.
     */
    public function getShiftColorAttribute(): string
    {
        return match($this->shift_type) {
            'Morning Shift' => 'blue',
            'Afternoon Shift' => 'orange',
            'Night Shift' => 'purple',
            default => 'gray',
        };
    }
}
