<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'term',
        'school_year',
        'curriculum_mode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

