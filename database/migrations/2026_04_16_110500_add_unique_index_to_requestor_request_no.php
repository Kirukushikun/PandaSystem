<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasDuplicates = DB::table('requestor')
            ->select('request_no')
            ->whereNotNull('request_no')
            ->groupBy('request_no')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicates) {
            throw new RuntimeException('Duplicate request_no values still exist in requestor. Run php artisan pan:fix-duplicate-request-nos before migrating.');
        }

        Schema::table('requestor', function (Blueprint $table) {
            $table->unique('request_no', 'requestor_request_no_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requestor', function (Blueprint $table) {
            $table->dropUnique('requestor_request_no_unique');
        });
    }
};
