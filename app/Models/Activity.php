<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'days_string', 'hour'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'inscriptions');
    }
}
