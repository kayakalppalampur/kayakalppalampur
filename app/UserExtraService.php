<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExtraService extends Model
{
    //

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'user_id',
        'service_id',
        'booking_id',
        'member_id',
        'service_start_date',
        'service_end_date',
        'status',
        'price',
        'is_child_driver'
    ];
    protected $table = 'user_extra_services';

    protected $appends = [
        'service_name'];

    public function getServiceNameAttribute()
    {
        if (!empty($this->service)) {
            return $this->service->name;
        }

        if ($this->is_child_driver != 0) {
            if ($this->is_child_driver == 1) {
                return "Child";
            }

            if ($this->is_child_driver == 2) {
                return "Driver";
            }
        }
    }

    public function service()
    {
        return $this->belongsTo('App\ExternalService', 'service_id');
    }

    public function booking()
    {
        return $this->belongsTo('App\Bookingroom', 'booking_id');
    }

    public function saveOrder()
    {
        OrderItem::create([
            'user_id' => $this->user_id,
            'booking_id' => $this->booking_id,
            'item_type' => get_class($this),
            'item_id' => $this->id,
            'amount' => $this->service->price,
        ]);
    }

    public function daysPrice($date = false)
    {
        $thischeckin = $this->service_start_date;
        $thischeckout = $this->service_end_date;
        if($thischeckin != null && $thischeckout != null){
            $price = $this->price;
            $now = strtotime($this->service_start_date); // or your date as well
            $your_date = strtotime($this->service_end_date);

            if (date("Y-m-d", strtotime($this->service_start_date)) <= date("Y-m-d")) {
                if ($your_date >= strtotime(date("Y-m-d")) && $date == true) {
                    $your_date = strtotime("today");
                }
            } else {
                if ($date == true) {
                    return 0;
                }
            }


            $datediff = $your_date - $now;

            $days = floor($datediff / (60 * 60 * 24));

            return $days * $price;
        }
        else{
            return 0;
        }
        
    }
}
