<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    const IS_ATTENDANT = 1;


    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;

    protected $fillable = [
        'name',
        'age',
        'gender',
        'id_proof',
        'user_id',
        'booking_id',
        'is_attendant',
        'status',
        'type',
        'building_id',
        'floor_number',
        'booking_type',
        'room_id',
        'bed_number',
        'check_in_date',
        'check_out_date',
        'is_child',
        'is_driver',
        'child_count',
        'driver_count',
    ];

    protected $table = 'members';


    protected $appends = [
        'check_in_date_date',
        'check_out_date_date'
    ];

    public function getCheckInDateDateAttribute()
    {
        return date("d-m-Y", strtotime($this->check_in_date));
    }

    public function getCheckOutDateDateAttribute()
    {
        return date("d-m-Y", strtotime($this->check_out_date));
    }

    public function building()
    {
        return $this->belongsTo('App\Building', 'building_id');
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function room()
    {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public function bookingRooms()
    {
        return $this->hasMany('App\BookingRoom', 'member_id');
    }

    public static function getRules()
    {
        return [
            'title' => 'required',
            /*'quantity' => 'required',*/
            'duration' => 'required',
            'department_id' => 'required'
            /* 'price' => 'required'*/
        ];
    }

    public function setData($data)
    {
        $this->name = $data->get("name");
        $this->age = $data->get("age");
        $this->gender = $data->get("gender");
        $this->booking_id = $data->get("booking_id");
        $this->user_id = $data->get('user_id');
        $this->is_attendant = self::IS_ATTENDANT;
        return $this;
    }

    public static function getGenderOptions($id = null)
    {
        $list = [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male',
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }

    public function getRoomDetails()
    {
        $room = BookingRoom::where('member_id', $this->id)->orderBy("created_at", 'DESC')->first();
        if ($room != null) {
            return $room->roomDetails();
        }
        return "";
    }

    public function getServiceDetails()
    {
        $html = "";
        $room = BookingRoom::where('member_id', $this->id)->orderBy("created_at", 'DESC')->first();
        if ($room != null) {
            $services = $room->userServices;
            if (!empty($services)) {
                foreach ($services as $service) {
                    $html .= "Name: " . $service->service_name . "(Rs." . $service->price . "/day)" . PHP_EOL;
                }
            }
        }
        return $html;
    }

    public function getAllServices()
    {
        $services = UserExtraService::where('member_id', $this->id)->get();
        return $services;
    }

    public function getServices()
    {
        $services = UserExtraService::where('member_id', $this->id)->get();
        $service_ar = [];
        if ($services->count() > 0) {
            foreach ($services as $service) {
                $service_ar[] = [
                    'service_name' => $service->service->name,
                    'service_price' => $service->service->price
                ];
            }
        }
        return $service_ar;
    }

    public function getPrice($room_id = null, $type = null)
    {
        $room = Room::find($room_id);
        if ($room_id == null) {
            $room = BookingRoom::where('member_id', $this->id)->orderBy("created_at", 'DESC')->first();
        }
        $price = 0;
        if ($room != null) {
            $price = $room->room_price;

            if ($type == Booking::BOOKING_TYPE_SINGLE_BED || $type == Booking::BOOKING_TYPE_DOUBLE_BED_SHARING) {
                $price = $room->bed_price;
            } elseif ($type == Booking::BOOKING_TYPE_DOUBLE_BED_EB || $type == Booking::BOOKING_TYPE_SINGLE_OCCUPANCY_EB) {
                $price = $price + $room->bed_price;
            }

        }

        return $price;
    }

    public function getRoomDates()
    {
        $room = BookingRoom::where('member_id', $this->id)->orderBy("created_at", 'DESC')->first();

        if ($room != null) {
            return $room->check_in_date . ' to ' . $room->check_out_date;/*
            return $room->getDates();*/
        }
        return "";
    }


    public function daysPrice($room_id = null, $ser = true, $current = true)
    {
        $room = BookingRoom::where('member_id', $this->id)->orderBy("created_at", 'DESC')->first();
        if ($room != null) {
            return $room->allDaysPrice(null, true, $current);
        }

        return 0;
        /*

        $price = $this->getPrice($room->room_id, $room->type);

        $now = strtotime($room->check_in_date); // or your date as well
        $your_date = strtotime($room->check_out_date);
        /*if ($your_date > date("Y-m-d")) {
            $your_date = date("Y-m-d");
        }*/
        /*$datediff = $your_date - $now;

        $days =  floor($datediff / (60 * 60 * 24));

        $booking_price = $days * $price;


        $total = $booking_price;
        if ($ser == true) {
            $service_price = 0;
            $services = $room->userServices;
            if (!empty($services)) {
                foreach ($services as $service) {
                    $service_price += $service->daysPrice();
                }
            }

            $total = $booking_price + $service_price;
        }
        return $total;*/
    }

    public function customDelete()
    {
        $rooms = $this->bookingRooms;

        foreach ($rooms as $room) {
            $services = $room->userServices;
            foreach ($services as $service) {
                $service->delete();
            }
            $room->delete();
        }

        if ($this->id_proof) {
            if (file_exists(storage_path() . '/app/' . $this->id_proof)) {
                @unlink(storage_path() . '/app/' . $this->id_proof);
            }
        }

        $this->delete();
    }
}
