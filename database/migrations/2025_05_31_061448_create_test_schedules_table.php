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
        Schema::create('test_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jobapply')->nullable();
            $table->dateTime('test_date');
            $table->string('test_address');
            $table->text('test_notes');
            $table->text('result_attachment');
            $table->text('result_notes');
            $table->integer('approved_by_1')->nullable();
            $table->integer('test_status')->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('ready_offering');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_schedules');
    }
};
