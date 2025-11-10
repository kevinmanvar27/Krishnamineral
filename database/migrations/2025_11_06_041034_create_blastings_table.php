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
        Schema::create('blastings', function (Blueprint $table) {
            $table->unsignedInteger('blasting_id')->primary()->autoIncrement()->comment('Primary key');
            $table->unsignedInteger('bnm_id')->comment('Foreign key to blaster_names table');
            $table->string('b_notes', 255)->collation('utf8mb4_general_ci')->nullable();
            $table->dateTime('date_time');
            $table->string('geliten', 255)->collation('utf8mb4_general_ci');
            $table->string('geliten_rate', 255)->collation('utf8mb4_general_ci');
            $table->string('geliten_total', 255)->collation('utf8mb4_general_ci');
            $table->string('df', 255)->collation('utf8mb4_general_ci');
            $table->string('df_rate', 255)->collation('utf8mb4_general_ci');
            $table->string('df_total', 255)->collation('utf8mb4_general_ci');
            $table->string('odvat', 255)->collation('utf8mb4_general_ci');
            $table->string('od_rate', 255)->collation('utf8mb4_general_ci');
            $table->string('od_total', 255)->collation('utf8mb4_general_ci');
            $table->text('controll')->collation('utf8mb4_general_ci');
            $table->string('gross_total', 255)->collation('utf8mb4_general_ci');
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('update_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blastings');
    }
};