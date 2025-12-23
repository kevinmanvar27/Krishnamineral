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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing work_timing_initiate_checking column and add as string
            $table->dropColumn('work_timing_initiate_checking');
            $table->string('work_timing_initiate_checking', 255)->nullable()->after('work_timing_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the string column and recreate as time
            $table->dropColumn('work_timing_initiate_checking');
            $table->time('work_timing_initiate_checking')->nullable()->after('work_timing_enabled');
        });
    }
};