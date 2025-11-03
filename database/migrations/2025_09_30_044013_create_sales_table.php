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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time')->useCurrent();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('transporter');
            $table->string('contact_number');
            $table->string('driver_contact_number');
            $table->integer('gross_weight')->nullable();
            $table->integer('tare_weight');
            $table->integer('net_weight')->nullable();
            $table->integer('party_weight')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->decimal('gst', 5, 2)->nullable();
            $table->foreignId('material_id')->nullable()->constrained('materials')->onDelete('cascade');
            $table->integer('loading_id')->nullable()->constrained('loadings')->onDelete('cascade');
            $table->integer('place_id')->nullable()->constrained('places')->onDelete('cascade');
            $table->foreignId('party_id')->nullable()->constrained('parties')->onDelete('cascade');
            $table->foreignId('royalty_id')->nullable()->constrained('royalties')->onDelete('cascade');
            $table->string('royalty_number')->nullable();
            $table->string('royalty_tone')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('cascade');
            $table->tinyInteger('carting_id')->nullable();
            $table->decimal('carting_rate' , 10, 2)->nullable();
            $table->tinyInteger('carting_radio')->default(0);  
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 for pending, 1 for completed, 2 for completed after pending load (audit required)');
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
