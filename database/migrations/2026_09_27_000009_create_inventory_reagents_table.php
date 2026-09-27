<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reagents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('name'); // CBC Lyse Reagent, SGPT Enzymatic Kit, Glucose GOD-POD
            $table->string('code')->unique(); // REAG-001
            $table->string('brand')->nullable(); // Sysmex, Roche, Beckman, Mindray
            $table->string('lot_number')->nullable();
            $table->string('unit')->default('Tests'); // Tests, mL, Vials, Kits
            $table->decimal('stock_quantity', 10, 2)->default(0);
            $table->decimal('min_alert_threshold', 10, 2)->default(10);
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->boolean('is_expired')->default(false);
            $table->text('storage_conditions')->nullable(); // 2-8°C, Keep dark
            $table->timestamps();
        });

        Schema::create('reagent_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reagent_id')->constrained('reagents')->cascadeOnDelete();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->decimal('quantity_per_test', 10, 4)->default(1); // e.g. 1 test or 0.5 mL
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reagent_consumptions');
        Schema::dropIfExists('reagents');
    }
};
