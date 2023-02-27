<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'quiz_winner',
        'quiz_master',
        'started_at',
        'location_id'
    ];

    /**
     * Get the location that owns the quiz.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getStartedAtAttribute( $date )
    {
        if( is_null( $date ) )
        {
            return $date;
        }
        return Carbon::createFromFormat( 'Y-m-d H:i:s', $date)->format( 'Y-m-d\TH:i' );
    }
}
