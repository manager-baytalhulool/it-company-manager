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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_serial_number_id')->constrained('journal_entry_serial_numbers');
            $table->foreignId('account_head_id')->constrained('account_heads');
            $table->foreignId('for_account_head_id')->constrained('account_heads');
            $table->double('debit');
            $table->double('credit');
            $table->timestamps();
            $table->softDeletes();

            /* Not for end users */
            $table->string('reference_type', 128)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->index(["reference_type", "reference_id"], null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
