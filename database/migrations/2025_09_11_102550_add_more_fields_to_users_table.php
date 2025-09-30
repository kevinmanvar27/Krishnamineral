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
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('contact_number_1')->nullable();
            $table->string('contact_number_2')->nullable();
            $table->date('joining_date')->nullable();

            // Files
            $table->string('user_photo_id')->nullable();
            $table->string('user_address_proof')->nullable();

            // Gender
            $table->tinyInteger('employee_gender')->nullable(); // 1=male,2=female

            // Insurance
            $table->tinyInteger('insurance')->default(1); //1=no,2=yes
            $table->string('insurance_name')->nullable();
            $table->string('insurance_policy_copy')->nullable();
            $table->date('insurance_issue_date')->nullable();
            $table->date('insurance_valid_date')->nullable();

            // Nominee
            $table->tinyInteger('nominee')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_mobile_number')->nullable();
            $table->string('nominee_photo_id')->nullable();
            $table->string('nominee_address_proof')->nullable();
            $table->tinyInteger('nominee_gender')->nullable();
            $table->date('nominee_birthdate')->nullable();
            $table->text('insurance_note')->nullable();

            // User type
            $table->tinyInteger('user_type')->default(1); //1=driver,2=operator,3=computer operator,4=other
            $table->string('department')->nullable();

            // Salary
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('licence')->nullable();

            // Bank
            $table->tinyInteger('bank')->default(1);
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_proof')->nullable();

            // Court
            $table->tinyInteger('court')->default(1);
            $table->string('court_case')->nullable();
            $table->json('court_case_files')->nullable(); 
            $table->string('court_case_close_file')->nullable();

            // Extra
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
