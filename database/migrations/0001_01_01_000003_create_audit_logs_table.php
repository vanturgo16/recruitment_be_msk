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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto-increment
            $table->string('username'); // not nullable
            $table->string('ip_address'); // not nullable
            $table->text('location'); // not nullable
            $table->string('access_from'); // not nullable
            $table->text('activity'); // not nullable
            $table->timestamp('created_at')->nullable(); // nullable
            $table->timestamp('updated_at')->nullable(); // nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
