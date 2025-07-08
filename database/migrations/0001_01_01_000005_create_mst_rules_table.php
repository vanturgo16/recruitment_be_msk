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
        Schema::create('mst_rules', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto-increment
            $table->string('rule_name'); // NOT NULL
            $table->string('rule_value'); // NOT NULL
            $table->boolean('is_active'); // NOT NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_rules');
    }
};
