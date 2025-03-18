<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_settings', function (Blueprint $table) {
            $table->string('screen_type')->default('check_in'); // Default to 'check_in' for visitor check-in screen
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_settings', function (Blueprint $table) {
            $table->dropColumn('screen_type');
        });
    }
};
