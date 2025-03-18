<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @return void
     */
    public function up(): void {
        Schema::create('field_settings', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void {
        Schema::dropIfExists('field_settings');
    }
};
