<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBooking extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISCHARGED = 2;

    protected $fillable = [
        'user_id',
        'booking_id',
        'status',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
