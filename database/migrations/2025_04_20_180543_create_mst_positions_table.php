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
        Schema::create('mst_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_dept'); // NOT NULL
            $table->string('position_name'); // NOT NULL
            $table->string('hie_level'); // NOT NULL
            $table->text('notes')->nullable(); // Nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_positions');
    }
};
