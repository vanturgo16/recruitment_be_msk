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
        Schema::create('signing_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jobapply')->nullable();
            $table->dateTime('sign_date');
            $table->string('sign_address');
            $table->text('sign_notes');
            $table->text('result_notes');
            $table->integer('approved_by_1')->nullable();
            $table->integer('sign_status')->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('ready_hired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signing_schedules');
    }
};
