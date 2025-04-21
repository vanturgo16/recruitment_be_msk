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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // NOT NULL
            $table->string('type'); // NOT NULL
            $table->string('name'); // NOT NULL
            $table->string('address'); // NOT NULL
            $table->string('province')->nullable(); // Nullable
            $table->string('city')->nullable(); // Nullable
            $table->string('district')->nullable(); // Nullable
            $table->string('subdistrict')->nullable(); // Nullable
            $table->string('postal_code')->nullable(); // Nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
