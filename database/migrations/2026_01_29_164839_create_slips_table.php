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
        Schema::create('slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained()->cascadeOnDelete();
            $table->integer('main_salary');
            $table->integer('overtime_pay')->default(0);
            $table->integer('meal_pay')->default(0);
            $table->integer('transportation_pay')->default(0);
            $table->integer('bonus')->default(0);
            $table->string('bonus_description')->nullable();

            $table->integer('late_deduction')->default(0);
            $table->integer('absent_deduction')->default(0);
            $table->integer('break_stuff_deduction')->default(0);
            $table->integer('other_deduction')->default(0);
            $table->string('other_deduction_description')->nullable();

            $table->integer('total_salary');
            $table->integer('total_deduction');
            $table->integer('total_net_salary');

            $table->enum('status', ['paid', 'draft', 'pending'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slips');
    }
};
