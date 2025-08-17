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
        Schema::create('requestor', function (Blueprint $table) {
            $table->id();
            $table->string('request_no');
            $table->json('is_deleted_by')->nullable();
            $table->enum('request_status', [
                'Draft', 
                'For Prep', 
                'For Approval', 
                'Returned to Requestor', 
                'Returned to HR', 
                'Approved', 
                'Rejected'
            ])->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('department')->nullable();
            $table->string('type_of_action')->nullable(); // or enum if fixed set
            $table->text('justification')->nullable();
            $table->string('supporting_file_url')->nullable();
            $table->string('requested_by')->nullable();
            $table->timestamp('submitted_at')->nullable(); // Set upon submission
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requestor');
    }
};
