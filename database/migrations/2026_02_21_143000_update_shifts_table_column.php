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
        Schema::table('shifts', function (Blueprint $table) {
            // Change employee_id to be an integer foreign key
            if (Schema::hasColumn('shifts', 'employee_id')) {
                $table->dropIndex(['employee_id']); // Drop existing index
                $table->dropColumn('employee_id'); // Drop existing column
            }
            
            // Add new employee_id as integer foreign key
            $table->unsignedBigInteger('employee_id')->after('id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->index('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropIndex(['employee_id']);
            $table->dropColumn('employee_id');
            
            // Add back the old string column
            $table->string('employee_id')->after('id');
            $table->index('employee_id');
        });
    }
};
