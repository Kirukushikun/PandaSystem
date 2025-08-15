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
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('requestor')->onDelete('cascade');
            $table->date('date_hired');
            $table->string('employment_status');
            $table->string('division');
            $table->date('date_of_effectivity'); // renamed for clarity
            $table->json('action_reference_data')->nullable();
            $table->text('remarks')->nullable();
            $table->string('prepared_by');
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
