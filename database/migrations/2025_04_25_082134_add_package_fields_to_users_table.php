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
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('package_start_date')->nullable()->after('role');
                $table->date('package_end_date')->nullable()->after('package_start_date');
                $table->string('package_type')->nullable()->after('package_end_date');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['package_start_date', 'package_end_date', 'package_type']);
        });
    }
};
