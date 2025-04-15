<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scenes', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('status');
            $table->timestamp('ended_at')->nullable()->after('started_at');
            $table->text('description')->nullable()->after('title');
            $table->boolean('is_private')->default(false)->after('ended_at');
        });
        
        DB::statement("ALTER TABLE scenes MODIFY COLUMN status ENUM('planning', 'active', 'completed') NOT NULL DEFAULT 'planning'");
        
        if (Schema::hasColumn('scenes', 'synopsis')) {
            DB::statement('UPDATE scenes SET description = synopsis WHERE description IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE scenes MODIFY COLUMN status ENUM('active', 'finished') NOT NULL DEFAULT 'active'");
        
        Schema::table('scenes', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'ended_at', 'description', 'is_private']);
        });
    }
};
