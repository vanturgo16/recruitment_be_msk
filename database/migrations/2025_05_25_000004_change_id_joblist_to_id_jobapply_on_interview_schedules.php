<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jobapply')->after('id')->nullable();
            $table->dropForeign(['id_joblist']);
            $table->dropColumn('id_joblist');
        });
    }

    public function down(): void
    {
        Schema::table('interview_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('id_joblist')->after('id');
            $table->dropColumn('id_jobapply');
        });
    }
};
