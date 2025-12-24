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

            // Change column type ONLY if it exists
            if (Schema::hasColumn('users', 'work_timing_initiate_checking')) {
                $table->integer('work_timing_initiate_checking')
                      ->nullable()
                      ->change();
            }

            // Add task tracking fields (NO `after()` to avoid SQL error)
            if (!Schema::hasColumn('users', 'task_start_time')) {
                $table->timestamp('task_start_time')->nullable();
            }

            if (!Schema::hasColumn('users', 'task_completed')) {
                $table->boolean('task_completed')->default(false);
            }

            if (!Schema::hasColumn('users', 'task_description')) {
                $table->string('task_description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'task_start_time')) {
                $table->dropColumn('task_start_time');
            }

            if (Schema::hasColumn('users', 'task_completed')) {
                $table->dropColumn('task_completed');
            }

            if (Schema::hasColumn('users', 'task_description')) {
                $table->dropColumn('task_description');
            }

            if (Schema::hasColumn('users', 'work_timing_initiate_checking')) {
                $table->string('work_timing_initiate_checking')
                      ->nullable()
                      ->change();
            }
        });
    }
};
