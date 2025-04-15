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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['ADVANCEMENT', 'APPLICATIONS', 'BUG_REPORTS', 'FEEDBACK', 'PITCH', 'REWORK', 'TP']);
            $table->string('title');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('handler_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['OPEN', 'CLOSED', 'APPROVED', 'REJECTED', 'CANCELED'])->default('OPEN');
            $table->foreignId('character_id')->nullable()->constrained('characters')->onDelete('set null');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
