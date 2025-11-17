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
        DB::statement("ALTER TABLE requestor MODIFY COLUMN request_status ENUM(
            'Draft',
            'For Head Approval',
            'For HR Prep',
            'For Confirmation',
            'For HR Approval',
            'For Resolution',
            'For Final Approval',
            'Returned to Requestor',
            'Returned to HR',
            'Rejected by Head',
            'Rejected by HR',
            'Approved',
            'Served',
            'Filed',
            'Withdrew',
            'Deleted'
        ) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE requestor MODIFY COLUMN request_status ENUM(
            'Draft',
            'For Head Approval',
            'For HR Prep',
            'For Confirmation',
            'For HR Approval',
            'For Resolution',
            'For Final Approval',
            'Returned to Requestor',
            'Returned to HR',
            'Rejected by Head',
            'Rejected by HR',
            'Approved',
            'Served',
            'Filed',
            'Withdrew'
        ) NULL");
    }
};
