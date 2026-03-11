<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'employment_status'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
