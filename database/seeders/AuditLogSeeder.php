<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->warn('No users found. Skipping audit log seeding.');
            return;
        }

        // Sample audit logs
        $auditLogs = [
            [
                'user_id' => $user->id,
                'action' => 'created',
                'subject_type' => 'App\Models\Shift',
                'subject_id' => 1,
                'description' => 'Created new shift for John Doe - Morning Shift',
                'new_values' => [
                    'employee_name' => 'John Doe',
                    'shift_type' => 'Morning Shift',
                    'schedule_start' => '09:00',
                    'schedule_end' => '17:00',
                    'days' => 'Monday, Tuesday, Wednesday'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'user_id' => $user->id,
                'action' => 'approved',
                'subject_type' => 'App\Models\Claim',
                'subject_id' => 1,
                'description' => 'Approved medical reimbursement claim for Jane Smith',
                'new_values' => [
                    'status' => 'approved',
                    'amount' => 5000,
                    'claim_type' => 'medical'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'action' => 'rejected',
                'subject_type' => 'App\Models\LeaveRequest',
                'subject_id' => 1,
                'description' => 'Rejected leave request for Mike Johnson - Insufficient leave balance',
                'new_values' => [
                    'status' => 'rejected',
                    'leave_type' => 'vacation',
                    'reason' => 'Insufficient leave balance'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'user_id' => $user->id,
                'action' => 'updated',
                'subject_type' => 'App\Models\Employee',
                'subject_id' => 1,
                'description' => 'Updated employee information for Sarah Wilson',
                'old_values' => [
                    'position' => 'Developer',
                    'department' => 'IT'
                ],
                'new_values' => [
                    'position' => 'Senior Developer',
                    'department' => 'Engineering'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $user->id,
                'action' => 'created',
                'subject_type' => 'App\Models\OvertimeRequest',
                'subject_id' => 1,
                'description' => 'Created overtime request for Tom Brown - 3 hours extra',
                'new_values' => [
                    'employee_name' => 'Tom Brown',
                    'overtime_hours' => 3,
                    'reason' => 'Project deadline'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'action' => 'deleted',
                'subject_type' => 'App\Models\Shift',
                'subject_id' => 5,
                'description' => 'Deleted shift for Alex Turner - Night Shift',
                'old_values' => [
                    'employee_name' => 'Alex Turner',
                    'shift_type' => 'Night Shift',
                    'schedule_start' => '22:00',
                    'schedule_end' => '06:00'
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
        ];

        foreach ($auditLogs as $log) {
            AuditLog::create($log);
        }

        $this->command->info('Audit logs seeded successfully!');
    }
}
