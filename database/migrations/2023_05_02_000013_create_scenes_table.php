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
        Schema::create('scenes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('location_id')->constrained()->onDelete('restrict');
            $table->text('synopsis')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('plot_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('creator_character_id')->constrained('characters')->onDelete('restrict');
            $table->enum('status', ['active', 'finished'])->default('active');
            $table->timestamp('last_activity_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenes');
    }
};
