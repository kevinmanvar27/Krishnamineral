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
            // Change work_timing_initiate_checking to integer for minutes
            $table->dropColumn('work_timing_initiate_checking');
            $table->integer('work_timing_initiate_checking')->nullable()->after('work_timing_enabled');
            
            // Add task tracking fields
            $table->timestamp('task_start_time')->nullable()->after('work_timing_initiate_checking');
            $table->boolean('task_completed')->default(false)->after('task_start_time');
            $table->string('task_description')->nullable()->after('task_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['task_start_time', 'task_completed', 'task_description']);
            $table->dropColumn('work_timing_initiate_checking');
            $table->string('work_timing_initiate_checking', 255)->nullable()->after('work_timing_enabled');
        });
    }
};
