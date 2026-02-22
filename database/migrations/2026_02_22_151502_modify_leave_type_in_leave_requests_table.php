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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('leave_type');
        });
        
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->string('leave_type')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('leave_type');
        });
        
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->enum('leave_type', ['sick', 'vacation', 'personal', 'maternity', 'emergency'])->after('position');
        });
    }
};
