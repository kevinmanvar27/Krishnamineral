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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('name');

            $table->string('tagline')->nullable();
            $table->string('favicon')->nullable();
            
            $table->string('gst_number', 15)->nullable()->unique();

            $table->text('address')->nullable();
            $table->text('location')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('whatsapp_number')->nullable();    
            
            
            $table->string('copyright')->nullable();
            
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            $table->string('authorized_signatory')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
