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
        Schema::create('preparer', function (Blueprint $table) {
            $table->id();

            // Relationship
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')
                ->references('id')
                ->on('requestor')
                ->onDelete('cascade');

            // Employment Details
            $table->text('date_hired');
            $table->text('employment_status');
            $table->text('division');

            // Duration of Effectivity
            $table->text('doe_from');
            $table->text('doe_to');

            // Compensation & References
            $table->text('wage_no')->nullable();
            $table->text('action_reference_data')->nullable();

            // Additional Info
            $table->text('remarks')->nullable();
            $table->boolean('has_allowances')->default(false);

            // Prepared & Approved
            $table->text('prepared_by')->nullable();
            $table->text('approved_by')->nullable();

            // System Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparer');
    }
};
