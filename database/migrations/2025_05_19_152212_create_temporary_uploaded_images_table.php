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
        Schema::create('temporary_uploaded_images', function (Blueprint $table) {
            $table->id();
            $table->morphs('uploadable');
            $table->uuid('uid')->nullable();
            $table->string('file_name');
            $table->string('file_url');
            $table->integer('size');
            $table->string('file_type')->nullable();
            $table->string('public_id');
            $table->string('collection_name');
            $table->string('thumbnail_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_uploaded_images');
    }
};
