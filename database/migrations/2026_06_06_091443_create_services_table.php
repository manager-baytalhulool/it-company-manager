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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug');
            $table->string('description');
            $table->string('image_path');
            $table->unsignedInteger('order_no');
            $table->string('icon');
            $table->string('color');

            $table->string("video_thumbnail")->nullable();
            $table->string("video_path")->nullable();

            // page_component is the name of the Vue component that will be used to render the service page
            $table->string("page_component")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
