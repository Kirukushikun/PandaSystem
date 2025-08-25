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
            $table->string('current_handler')->default('requestor');
            $table->enum('request_status', [
                // Requestor -> Division Head
                'Draft', 
                'For Head Approval',

                // Division Head -> HR Preparer
                'For HR Prep',

                // HR Preparer -> Division Head
                'For Confirmation',

                // Division Head -> HR Approver/Preparer
                'For HR Approval',
                'For Resolution',

                // HR Approver -> Final Approver
                'For Final Approval',

                'Returned to Requestor', 
                'Returned to HR',

                // General Status
                'Approved', // Disabled
                'Rejected', // Disabled
                'Withdrew' // Disabled
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
