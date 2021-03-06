<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentDischargeBooking extends Model
{
    //

    protected $fillable = [
        'booking_id',
        'department_id',
        'summary',
        'things_to_avoid',
        'follow_up_advice',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
