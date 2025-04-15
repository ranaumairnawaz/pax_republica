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
        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('faction_id')->nullable()->after('species_id')->constrained()->nullOnDelete();
            $table->foreignId('faction_rank_id')->nullable()->after('faction_id')->constrained()->nullOnDelete();
            $table->string('occupation')->nullable()->after('name');
            $table->text('biography')->nullable()->after('occupation');
            $table->text('appearance')->nullable()->after('biography');
            $table->string('homeworld')->nullable()->after('appearance');
            $table->integer('credits')->default(0)->after('homeworld');
            $table->integer('level')->default(1)->after('xp');
            $table->string('image_url')->nullable()->after('level');
            $table->boolean('is_npc')->default(false)->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropForeign(['faction_id']);
            $table->dropForeign(['faction_rank_id']);
            $table->dropColumn([
                'faction_id',
                'faction_rank_id',
                'occupation',
                'biography',
                'appearance',
                'homeworld',
                'credits',
                'level',
                'image_url',
                'is_npc'
            ]);
        });
    }
};
