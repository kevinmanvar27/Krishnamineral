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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 20);
            $table->string('vendor_name', 255);
            $table->string('contact_person', 255);
            $table->string('mobile', 13);
            $table->string('telephone', 15)->nullable();
            $table->string('email_id', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('country', 55)->nullable();
            $table->string('state', 55)->nullable();
            $table->string('city', 55)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->text('address')->nullable();
            $table->string('bank_proof', 255)->nullable();
            $table->text('payment_conditions')->nullable();
            $table->string('visiting_card', 255)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};