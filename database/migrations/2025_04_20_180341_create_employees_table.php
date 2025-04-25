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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_no')->nullable(); // Nullable
            $table->string('email'); // NOT NULL
            $table->integer('id_position'); // NOT NULL
            $table->integer('placement_id'); // NOT NULL
            $table->string('reportline_1'); // NOT NULL
            $table->string('reportline_2'); // NOT NULL
            $table->string('reportline_3'); // NOT NULL
            $table->string('reportline_4')->nullable(); // Nullable
            $table->string('reportline_5')->nullable(); // Nullable
            $table->boolean('is_active'); // NOT NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
