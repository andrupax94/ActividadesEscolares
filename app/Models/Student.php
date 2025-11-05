<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'grade', 'age'];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'inscriptions');
    }
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
