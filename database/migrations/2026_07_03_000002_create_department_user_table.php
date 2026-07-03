<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Requestor and Head are independent: a department can have more than
            // one head (co-heads), a user can be a requestor without heading a
            // department, and a user can head a department without being a
            // requestor for it (e.g. an overall approver of division heads).
            $table->boolean('is_requestor')->default(true);
            $table->boolean('is_head')->default(false);
            $table->timestamps();

            $table->primary(['department_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_user');
    }
};
