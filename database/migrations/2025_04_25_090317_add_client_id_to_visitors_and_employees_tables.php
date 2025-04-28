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
        Schema::table('visitors_and_employees_tables', function (Blueprint $table) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
            });

            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors_and_employees_tables', function (Blueprint $table) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->dropColumn('client_id');
            });

            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('client_id');
            });
        });
    }
};
