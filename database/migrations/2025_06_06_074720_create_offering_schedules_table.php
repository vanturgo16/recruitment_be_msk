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
        Schema::create('offering_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jobapply')->nullable();
            $table->dateTime('offering_date');
            $table->string('offering_address');
            $table->text('offering_notes');
            $table->text('result_attachment');
            $table->text('result_notes');
            $table->integer('approved_by_1')->nullable();
            $table->integer('offering_status')->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('ready_mcu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offering_schedules');
    }
};
