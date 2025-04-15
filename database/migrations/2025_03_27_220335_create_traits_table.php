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
        Schema::create('character_traits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['positive', 'negative', 'neutral']);
            $table->integer('xp_cost'); // Positive for advantages, negative for disadvantages
            $table->boolean('restricted')->default(false);
            $table->json('modifiers')->nullable(); // JSON to store any attribute/skill modifiers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_traits');
    }
};
