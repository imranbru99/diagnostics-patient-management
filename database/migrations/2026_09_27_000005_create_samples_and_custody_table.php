<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique(); // SMP-2026-00001
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('sample_type'); // Blood, Urine, etc.
            $table->string('collection_tube')->nullable(); // EDTA, Serum, etc.
            $table->enum('status', [
                'collected',
                'in_transit',
                'received_at_lab',
                'in_testing',
                'verified',
                'rejected'
            ])->default('collected');
            $table->timestamp('collected_at')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('rejection_code')->nullable(); // HEMOLYZED, CLOTTED, INSUFFICIENT_VOLUME, LEAKING
            $table->text('rejection_reason')->nullable();
            $table->boolean('recollection_required')->default(false);
            $table->timestamp('recollection_requested_at')->nullable();
            $table->timestamps();
        });

        Schema::create('sample_custody_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // Collected, Dispatched, Received in Central Lab, Placed in Analyzer, Verified, Rejected
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_custody_logs');
        Schema::dropIfExists('samples');
    }
};
