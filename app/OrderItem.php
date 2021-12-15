<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
    protected $fillable = [
        'user_id',
        'booking_id',
        'item_type',
        'item_id',
        'amount',
        'transaction_id',
        'status'
    ];

    protected $table = 'order_items';

    public function getType()
    {
        if ($this->item_type == 'App\Booking') {
            return "Accommodation";
        }

        if ($this->item_type == "App\UserExtraService") {
            return "Extra Services";
        }

        if ($this->item_type == 'App\User') {
            return "Basic";
        }
        return "Not Listed";
    }

}

