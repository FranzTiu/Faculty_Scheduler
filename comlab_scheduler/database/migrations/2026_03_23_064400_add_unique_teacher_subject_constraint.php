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
        // First, remove any existing duplicates (keep the one with lowest id)
        $duplicates = \Illuminate\Support\Facades\DB::select("
            SELECT t1.id
            FROM teachers t1
            INNER JOIN teachers t2
            ON t1.name = t2.name
            AND t1.subject_id = t2.subject_id
            AND t1.subject_id IS NOT NULL
            AND t1.id > t2.id
        ");

        if (count($duplicates) > 0) {
            $ids = array_map(fn($d) => $d->id, $duplicates);
            \App\Models\Teacher::whereIn('id', $ids)->delete();
        }

        Schema::table('teachers', function (Blueprint $table) {
            $table->unique(['name', 'subject_id'], 'teachers_name_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropUnique('teachers_name_subject_unique');
        });
    }
};
