<?php

use App\Models\Semester;
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
            if (!Schema::hasColumn('subjects', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('id')->constrained('semesters')->nullOnDelete();
            }
            if (!Schema::hasColumn('subjects', 'year_level')) {
                $table->unsignedTinyInteger('year_level')->nullable()->after('subject_name');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('id')->constrained('semesters')->nullOnDelete();
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('id')->constrained('semesters')->nullOnDelete();
            }
        });

        // Safe index handling for subjects
        try {
            Schema::table('subjects', function (Blueprint $table) {
                // Remove old unique if it exists
                try { $table->dropUnique(['subject_code']); } catch (\Throwable $e) {}
                
                // Add new composite unique
                $table->unique(['semester_id', 'subject_code'], 'subjects_semester_code_unique');
            });
        } catch (\Throwable $e) {}

        // Safe index handling for rooms
        try {
            Schema::table('rooms', function (Blueprint $table) {
                $table->unique(['semester_id', 'room_name'], 'rooms_semester_name_unique');
            });
        } catch (\Throwable $e) {}

        // Ensure at least one semester exists for backfilling
        $count = \DB::table('semesters')->count();
        if ($count === 0) {
            \DB::table('semesters')->insert([
                'term' => '1st',
                'school_year' => '2025-2026',
                'curriculum_mode' => 'custom',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $defaultId = \DB::table('semesters')->orderBy('id')->value('id');

        if ($defaultId) {
            \DB::table('subjects')->whereNull('semester_id')->update(['semester_id' => $defaultId]);
            \DB::table('rooms')->whereNull('semester_id')->update(['semester_id' => $defaultId]);
            \DB::table('schedules')->whereNull('semester_id')->update(['semester_id' => $defaultId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'semester_id')) {
                $table->dropConstrainedForeignId('semester_id');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            try { $table->dropUnique('rooms_semester_name_unique'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('rooms', 'semester_id')) {
                $table->dropConstrainedForeignId('semester_id');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            try { $table->dropUnique('subjects_semester_code_unique'); } catch (\Throwable $e) {}
            try { $table->unique('subject_code'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('subjects', 'year_level')) {
                $table->dropColumn('year_level');
            }
            if (Schema::hasColumn('subjects', 'semester_id')) {
                $table->dropConstrainedForeignId('semester_id');
            }
        });

        Schema::dropIfExists('semesters');
    }
};

