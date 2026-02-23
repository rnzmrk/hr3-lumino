<?php

namespace App\Http\Controllers;

use App\Services\AuditLogService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testAudit()
    {
        // Test audit logging
        AuditLogService::log(
            'created',
            'App\Models\Test',
            1,
            'Test audit log entry - this should appear in audit logs',
            null,
            ['test_field' => 'test_value', 'timestamp' => now()]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Test audit log created! Check the audit logs page.'
        ]);
    }
}
