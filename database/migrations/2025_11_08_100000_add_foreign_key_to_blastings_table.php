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
        Schema::table('blastings', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('bnm_id')->references('bnm_id')->on('blaster_names');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blastings', function (Blueprint $table) {
            $table->dropForeign(['bnm_id']);
        });
    }
};