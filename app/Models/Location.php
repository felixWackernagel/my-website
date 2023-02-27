<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'phone',
        'address',
        'photo'
    ];

    /**
     * Get the quizzes for the location.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
