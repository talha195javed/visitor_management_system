<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('customer_subscriptions', function (Blueprint $table) {
            $table->boolean('auto_renew')->default(true)->after('status');
            $table->timestamp('last_renewed_at')->nullable()->after('end_date');
            $table->timestamp('last_renewal_failed_at')->nullable()->after('last_renewed_at');
            $table->text('renewal_failure_reason')->nullable()->after('last_renewal_failed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_subscriptions', function (Blueprint $table) {
            //
        });
    }
};
