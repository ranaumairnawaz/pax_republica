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
        Schema::create('vehicle_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('type'); // starfighter, transport, capital, speeder, walker, other
            $table->string('size'); // tiny, small, medium, large, huge, gargantuan
            $table->integer('crew_min')->default(1);
            $table->integer('crew_max')->nullable();
            $table->integer('passengers')->default(0);
            $table->integer('cargo_capacity')->default(0); // in kg
            $table->integer('consumables')->default(0); // in days
            $table->string('speed')->nullable();
            $table->string('hyperspace_speed')->nullable();
            $table->integer('hull_points');
            $table->integer('shield_points')->default(0);
            $table->integer('armor')->default(0);
            $table->text('weapons')->nullable();
            $table->integer('base_cost');
            $table->boolean('is_restricted')->default(false);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_templates');
    }
};
