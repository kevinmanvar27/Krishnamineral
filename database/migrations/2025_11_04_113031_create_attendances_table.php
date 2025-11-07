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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->tinyInteger('type_attendance')->default(1); // 1=present, 2=absent, 3=absent paid
            $table->tinyInteger('extra_hours')->default(0);
            $table->integer('driver_tuck_trip')->default(0);
            $table->date('date');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('cron_jobs')->default(0);
            $table->timestamps();
            
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};