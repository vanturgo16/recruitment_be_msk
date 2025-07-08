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
        Schema::create('joblists', function (Blueprint $table) {
            $table->id();
            $table->integer('id_position'); // NOT NULL
            $table->date('rec_date_start'); // NOT NULL
            $table->date('rec_date_end')->nullable(); // Nullable
            $table->text('jobdesc'); // NOT NULL
            $table->text('requirement'); // NOT NULL
            $table->string('min_education')->nullable(); // Nullable
            $table->integer('min_yoe')->nullable(); // Nullable
            $table->integer('min_age')->nullable(); // Nullable
            $table->integer('max_candidate')->nullable(); // Nullable
            $table->integer('position_req_user'); // NOT NULL
            $table->integer('number_of_applicant'); // NOT NULL
            $table->boolean('is_active'); // NOT NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joblists');
    }
};
