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
        Schema::create('blaster_names', function (Blueprint $table) {
            $table->unsignedInteger('bnm_id')->primary()->autoIncrement()->comment('Primary key');
            $table->string('b_name', 255)->collation('utf8mb4_general_ci');
            $table->string('phone_no', 20)->collation('utf8mb4_general_ci');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blaster_names');
    }
};