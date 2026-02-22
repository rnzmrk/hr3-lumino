<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if employee_id column exists, if not add it
        if (!Schema::hasColumn('employees', 'employee_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('employee_id')->unique()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'employee_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('employee_id');
            });
        }
    }
};
