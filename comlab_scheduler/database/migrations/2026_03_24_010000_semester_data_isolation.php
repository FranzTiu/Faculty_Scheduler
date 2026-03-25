<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add unique constraint on semesters(term, school_year) to prevent duplicates
        try {
            Schema::table('semesters', function (Blueprint $table) {
                $table->unique(['term', 'school_year'], 'semesters_term_school_year_unique');
            });
        } catch (\Throwable $e) {
            // Constraint may already exist
        }

        // 2. Add semester_id to teachers table for per-semester isolation
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'semester_id')) {
                $table->foreignId('semester_id')->nullable()->after('id')->constrained('semesters')->nullOnDelete();
            }
        });

        // Backfill existing teachers with the active semester
        $defaultId = \DB::table('semesters')->where('is_active', true)->value('id')
            ?? \DB::table('semesters')->orderBy('id')->value('id');
        if ($defaultId) {
            \DB::table('teachers')->whereNull('semester_id')->update(['semester_id' => $defaultId]);
        }

        // 3. Add is_default flag to subjects and rooms
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('year_level');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('campus');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'semester_id')) {
                $table->dropConstrainedForeignId('semester_id');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });

        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });

        try {
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropUnique('semesters_term_school_year_unique');
            });
        } catch (\Throwable $e) {}
    }
};
