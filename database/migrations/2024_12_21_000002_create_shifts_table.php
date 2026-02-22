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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name');
            $table->string('employee_id');
            $table->enum('shift_type', ['Morning Shift', 'Afternoon Shift', 'Night Shift']);
            $table->time('schedule_start');
            $table->time('schedule_end');
            $table->string('days');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('employee_id');
            $table->index('shift_type');
            $table->index('employee_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
