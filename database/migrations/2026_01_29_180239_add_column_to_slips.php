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
        Schema::table('slips', function (Blueprint $table) {
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slips', function (Blueprint $table) {
            $table->dropColumn(['period_start', 'period_end']);
        });
    }
};
