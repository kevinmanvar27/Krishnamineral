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
        Schema::table('sales', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0 for pending, 1 for completed, 2 for completed after pending load (audit required)')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0 for pending, 1 for completed')->change();
        });
    }
};
