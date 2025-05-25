<?php
// 2025_05_25_000001_add_approved_reason_1_to_job_applies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('job_applies', function (Blueprint $table) {
            $table->text('approved_reason_1')->nullable()->after('approved_at_1');
        });
    }

    public function down()
    {
        Schema::table('job_applies', function (Blueprint $table) {
            $table->dropColumn('approved_reason_1');
        });
    }
};
