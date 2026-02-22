<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'position',
        'department',
    ];

    /**
     * Scope to filter by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to filter by position.
     */
    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Get available departments.
     */
    public static function getDepartments(): array
    {
        return [
            'IT Department',
            'Human Resources',
            'Sales',
            'Marketing',
            'Operations',
            'Finance',
            'Customer Service',
            'Administration',
        ];
    }

    /**
     * Get available positions.
     */
    public static function getPositions(): array
    {
        return [
            'Software Developer',
            'Project Manager',
            'HR Manager',
            'Sales Representative',
            'Marketing Specialist',
            'Operations Manager',
            'Financial Analyst',
            'Customer Service Representative',
            'System Administrator',
            'Business Analyst',
            'Team Lead',
            'Department Head',
        ];
    }
}
