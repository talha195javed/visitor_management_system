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
        Schema::create('company_info', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->string('hr_email')->unique();
            $table->string('welcome_screen_image')->nullable();
            $table->string('main_screen_image')->nullable();
            $table->string('remaining_screen_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
