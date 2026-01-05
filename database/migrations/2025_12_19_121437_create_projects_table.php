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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->string('name');
            $table->decimal('amount')->nullable();
            $table->decimal('original_amount')->nullable();
            $table->decimal('paid')->default(0);
            $table->boolean("is_available")->default(true);
            $table->boolean("is_duplicable")->default(false);
            $table->boolean("is_sellable")->default(false);
            $table->string("live_url")->nullable();
            $table->string("demo_url")->nullable();
            $table->date("started_at");
            $table->boolean("is_live")->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
