<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_name' => 'John Smith',
                'position' => 'Software Developer',
                'department' => 'IT Department',
            ],
            [
                'employee_name' => 'Sarah Johnson',
                'position' => 'HR Manager',
                'department' => 'Human Resources',
            ],
            [
                'employee_name' => 'Mike Wilson',
                'position' => 'Sales Representative',
                'department' => 'Sales',
            ],
            [
                'employee_name' => 'Emily Davis',
                'position' => 'Marketing Specialist',
                'department' => 'Marketing',
            ],
            [
                'employee_name' => 'Robert Brown',
                'position' => 'Operations Manager',
                'department' => 'Operations',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
