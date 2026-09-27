<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique(); // APT-2026-00001
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('test_id')->nullable()->constrained('tests')->nullOnDelete();
            $table->foreignId('test_package_id')->nullable()->constrained('test_packages')->nullOnDelete();
            $table->date('appointment_date');
            $table->string('time_slot')->default('09:00 - 10:00');
            $table->string('token_number')->nullable(); // e.g. T-101
            $table->enum('queue_status', [
                'booked',
                'checked_in',
                'called',
                'in_service',
                'completed',
                'cancelled',
                'no_show'
            ])->default('booked');
            $table->boolean('is_walk_in')->default(false);
            $table->text('symptoms_chief_complaint')->nullable(); // Feeds into AI package recommender
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
