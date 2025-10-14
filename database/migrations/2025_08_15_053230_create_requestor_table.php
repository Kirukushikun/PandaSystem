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

            // 🔹 Basic Info
            $table->string('request_no');
            $table->text('confidentiality', ['manila', 'tarlac'])->nullable();

            // 🔹 Employee Details
            $table->string('farm')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('department')->nullable();
            $table->string('type_of_action')->nullable(); // or enum if fixed set
            $table->text('justification')->nullable();
            
            // 🔹 Attachments
            $table->string('supporting_file_url')->nullable();
            $table->text('supporting_file_name')->nullable();

            // 🔹 Workflow & Status
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

                'Rejected by Head',
                'Rejected by HR',

                // General Status
                'Approved',
                'Served',
                'Filed',
                'Withdrew'
            ])->nullable();

            $table->string('current_handler')->default('requestor');
            $table->json('is_deleted_by')->nullable();

            // 🔹 References
            $table->text('requested_by')->nullable();
            $table->text('requestor_id')->nullable();
            $table->text('divisionhead_id')->nullable();
            $table->text('hr_id')->nullable();

            // 🔹 Timestamps
            $table->timestamp('submitted_at')->nullable();
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
