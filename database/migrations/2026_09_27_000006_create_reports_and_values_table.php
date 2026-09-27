<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique(); // RPT-2026-00001
            $table->string('slug')->unique(); // Universal slug for clean URL: /report/{slug}
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('pathologist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'preliminary', 'verified', 'published'])->default('draft');
            $table->text('clinical_impression')->nullable();
            $table->longText('narrative_findings')->nullable(); // For imaging/biopsy/microbiology
            
            // AI Hub Powered Fields
            $table->longText('ai_summary')->nullable(); // Plain-language explanation for patients
            $table->json('ai_critical_flags')->nullable(); // Auto-flagged critical/abnormal parameters
            $table->longText('ai_doctor_notes')->nullable(); // Doctor copilot differential suggestions (disclaimer attached)
            $table->timestamp('ai_analyzed_at')->nullable();

            // Verification & Security
            $table->string('verification_hash')->unique()->nullable(); // SHA-256 for authenticating paper & PDF reports
            $table->string('qr_code_url')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('published_at')->nullable();
            
            // Delivery tracking
            $table->json('delivery_channels')->nullable(); // ['portal', 'whatsapp', 'email', 'print']
            $table->timestamps();
        });

        Schema::create('report_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('test_parameter_id')->constrained('test_parameters')->cascadeOnDelete();
            $table->decimal('value_numeric', 12, 4)->nullable();
            $table->string('value_text')->nullable();
            $table->enum('flag', ['normal', 'low', 'high', 'critical_low', 'critical_high'])->default('normal');
            $table->string('reference_range_snapshot')->nullable(); // e.g. "13.0 - 17.5 g/dL"
            $table->string('unit')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_values');
        Schema::dropIfExists('reports');
    }
};
