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
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('package_type', ['basic', 'professional', 'enterprise']);
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->string('payment_intent_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('status', ['active', 'canceled', 'expired'])->default('active');
            $table->string('ip_address')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();

            $table->index('customer_email');
            $table->index('payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_subscriptions');
    }
};
