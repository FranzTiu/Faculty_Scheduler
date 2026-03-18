<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $fillable = ['semester_id', 'subject_code', 'subject_name', 'year_level'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
