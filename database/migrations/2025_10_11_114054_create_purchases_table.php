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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time')->useCurrent();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('transporter');
            $table->string('contact_number');
            $table->string('driver_contact_number');
            $table->integer('gross_weight')->nullable();
            $table->integer('tare_weight');
            $table->integer('net_weight')->nullable();
            $table->foreignId('material_id')->nullable()->constrained('materials')->onDelete('cascade');
            $table->integer('loading_id')->nullable()->constrained('loadings')->onDelete('cascade');
            $table->integer('quarry_id')->nullable()->constrained('purchase_quarries')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('purchase_receivers')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->tinyInteger('carting_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
