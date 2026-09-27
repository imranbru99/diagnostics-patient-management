<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // DOC-001
            $table->string('name');
            $table->string('designation')->nullable(); // MBBS, FCPS, MD, etc.
            $table->string('department')->nullable(); // Cardiology, Neurology, General Medicine
            $table->string('hospital_affiliation')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('default_commission_rate', 5, 2)->default(10.00); // 10.00%
            $table->string('payout_method')->nullable(); // Bank Transfer, bKash, Cash
            $table->text('payout_details')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('invoice_id')->nullable()->index();
            $table->decimal('order_total', 12, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->enum('status', ['accrued', 'approved', 'paid', 'cancelled'])->default('accrued');
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
        Schema::dropIfExists('doctors');
    }
};
