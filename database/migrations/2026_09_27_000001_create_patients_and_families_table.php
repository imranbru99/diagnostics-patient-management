<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('mrn')->unique(); // Medical Record Number: MRN-2026-0001
            $table->string('slug')->unique(); // Universal slug support
            $table->string('name');
            $table->string('phone')->index();
            $table->string('email')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->date('date_of_birth')->nullable();
            $table->integer('age_years')->nullable();
            $table->integer('age_months')->nullable();
            $table->integer('age_days')->nullable();
            $table->string('blood_group', 10)->nullable(); // A+, B+, AB+, O+, etc.
            $table->text('address')->nullable();
            $table->string('national_id')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('photo')->nullable();
            $table->string('portal_pin')->nullable(); // For quick patient portal login
            $table->timestamps();
        });

        Schema::create('patient_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('relative_patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('relationship'); // Parent, Child, Spouse, Sibling, Guardian
            $table->boolean('can_view_reports')->default(true);
            $table->timestamps();
        });

        Schema::create('patient_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->enum('document_type', ['old_report', 'prescription', 'id_proof', 'insurance_card', 'other'])->default('old_report');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_documents');
        Schema::dropIfExists('patient_families');
        Schema::dropIfExists('patients');
    }
};
