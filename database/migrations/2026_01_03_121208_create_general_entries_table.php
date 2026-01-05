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
        Schema::create('general_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('serial_no');
            $table->unsignedInteger('account_head_id');
            $table->unsignedInteger('for_account_head_id');
            $table->double('debit');
            $table->double('credit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_entries');
    }
};
