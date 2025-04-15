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
        Schema::create('mod_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // weapon, defense, propulsion, sensor, utility, special
            $table->json('effects'); // JSON object with effect keys and values
            $table->boolean('is_restricted')->default(false);
            $table->integer('cost');
            $table->integer('installation_difficulty')->default(1); // 1-4, easy to very hard
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mod_templates');
    }
};
