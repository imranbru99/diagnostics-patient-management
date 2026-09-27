<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // e.g. HEM, BIO, RAD, MIC
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_category_id')->constrained('test_categories')->cascadeOnDelete();
            $table->string('code')->unique(); // CBC, LFT, KFT, CXR, TSH
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sample_type')->default('Blood'); // Blood, Urine, Stool, Sputum, Swab, Imaging, Tissue
            $table->string('collection_tube')->nullable(); // EDTA (Purple), Serum/Gel (Gold), Fluoride (Gray), Plain (Red)
            $table->text('specimen_instructions')->nullable(); // Fasting 8-10 hours, morning first void
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->integer('tat_hours')->default(4); // Turnaround time in hours
            $table->string('method')->nullable(); // Automated Analyzer, ELISA, PCR, Digital Radiography
            $table->enum('report_type', ['numeric_tabular', 'narrative', 'imaging_findings'])->default('numeric_tabular');
            $table->text('clinical_significance')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->string('name'); // Hemoglobin, Platelet Count, Total Bilirubin
            $table->string('code'); // HGB, PLT, TBIL
            $table->string('unit')->nullable(); // g/dL, mg/dL, 10^3/uL, U/L, %
            $table->string('default_value')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });

        Schema::create('test_reference_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_parameter_id')->constrained('test_parameters')->cascadeOnDelete();
            $table->enum('gender', ['all', 'male', 'female'])->default('all');
            $table->integer('age_min_days')->default(0);
            $table->integer('age_max_days')->default(43800); // 120 years
            $table->decimal('min_value', 12, 4)->nullable();
            $table->decimal('max_value', 12, 4)->nullable();
            $table->decimal('critical_low', 12, 4)->nullable();
            $table->decimal('critical_high', 12, 4)->nullable();
            $table->string('text_range')->nullable(); // e.g. "Negative", "< 5.0", "13.0 - 17.5"
            $table->text('interpretation_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('test_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // PKG-EXEC, PKG-DIAB, PKG-WOMEN
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('test_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_package_id')->constrained('test_packages')->cascadeOnDelete();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_package_items');
        Schema::dropIfExists('test_packages');
        Schema::dropIfExists('test_reference_ranges');
        Schema::dropIfExists('test_parameters');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('test_categories');
    }
};
