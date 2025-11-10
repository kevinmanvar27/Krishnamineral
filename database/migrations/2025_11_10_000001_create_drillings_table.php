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
        Schema::create('drillings', function (Blueprint $table) {
            $table->id('drilling_id');
            $table->unsignedBigInteger('dri_id');
            $table->string('d_notes', 255)->nullable(); // Allow NULL values
            $table->dateTime('date_time');
            $table->text('hole');
            $table->string('gross_total', 255);
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('update_by')->nullable();
            $table->timestamps();
            
            $table->foreign('dri_id')->references('dri_id')->on('drilling_names');
            $table->foreign('update_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drillings');
    }
};