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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('employee_name');
            $table->enum('claim_type', ['travel', 'medical', 'food', 'office_supplies', 'training', 'others']);
            $table->text('description');
            $table->decimal('amount', 10, 2); // Amount in pesos
            $table->date('date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index(['employee_id', 'status']);
            $table->index(['claim_type', 'status']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
