<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Users allowed to see/confirm Manila-confidentiality PANs regardless
            // of department. Manila PANs are hidden from regular division heads,
            // even ones who head the same department, and shown only to users
            // with this flag set.
            $table->boolean('is_confidentiality_approver')->default(false)->after('access');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_confidentiality_approver');
        });
    }
};
