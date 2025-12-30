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
        Schema::create('mobile_offers', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('mobile_name_language_when_uploaded');
            $table->string('name_in_english');
            $table->string('name_in_arabic');
            $table
                ->integer('price_in_usd')
                ->nullable();
            $table
                ->boolean('is_sold')
                ->default(false);
            $table
                ->string('ram')
                ->nullable();
            $table
                ->string('storage')
                ->nullable();
            $table
                ->integer('battery_size')
                ->nullable();
            $table
                ->integer('battery_health')
                ->nullable();
            $table
                ->integer('number_of_sims')
                ->nullable();
            $table
                ->integer('number_of_esims')
                ->nullable();
            $table
                ->string(column: 'color')
                ->nullable();
            $table
                ->decimal('screen_size')
                ->nullable();
            $table
                ->string('screen_type')
                ->nullable();
            $table
                ->string('cpu')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_offers');
    }
};
