<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $fillable = ['semester_id', 'room_name', 'campus'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
