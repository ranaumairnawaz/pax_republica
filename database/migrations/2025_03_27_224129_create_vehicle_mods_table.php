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
        Schema::create('vehicle_mods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('mod_template_id')->constrained()->onDelete('cascade');
            $table->timestamp('installation_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure each mod can only be installed once per vehicle
            $table->unique(['vehicle_id', 'mod_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_mods');
    }
};
