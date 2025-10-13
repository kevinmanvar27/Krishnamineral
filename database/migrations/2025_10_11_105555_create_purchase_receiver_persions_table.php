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
        Schema::create('purchase_receiver_persions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('purchase_receivers')->onDelete('cascade');
            $table->string('persions');
            $table->string('persion_contact_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_receiver_persions');
    }
};
