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
        Schema::create('npcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator
            $table->foreignId('species_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('faction_rank_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('occupation')->nullable();
            $table->text('description')->nullable();
            $table->text('appearance')->nullable();
            $table->string('current_location')->nullable();
            $table->enum('importance', ['minor', 'supporting', 'major'])->default('minor');
            $table->boolean('is_public')->default(false); // Whether other GMs can use this NPC
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npcs');
    }
};
