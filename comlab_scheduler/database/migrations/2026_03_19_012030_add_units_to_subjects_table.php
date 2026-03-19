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
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'units')) {
                $table->unsignedInteger('units')->default(3)->after('year_level');
            }
            if (!Schema::hasColumn('subjects', 'room_id')) {
                $table->foreignId('room_id')->nullable()->after('units')->constrained('rooms')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'room_id')) {
                $table->dropConstrainedForeignId('room_id');
            }
            if (Schema::hasColumn('subjects', 'units')) {
                $table->dropColumn('units');
            }
        });
    }
};
