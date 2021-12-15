<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    const BED_TYPE_SINGLE = 0;
    const BED_TYPE_DOUBLE = 1;

    const IS_BLOCKED = 1;
    const IS_AVAILABLE = 0;

    const GENDER_MALE = 2;
    const GENDER_FEMALE = 1;
    const GENDER_NA = 3;

    protected $fillable = [
        'id',
        'room_number',
        'room_type_id',
        'gender',
        'building_id',
        'floor_number',
        'bed_type',
        'bed_count',
        'is_blocked',
        'room_price',
        'bed_price',
        'services'
    ];
    protected $table = 'rooms';

    public static function getFloorNumber($no)
    {
        if ($no == 1)
            return "Ground Floor";
        if ($no == 2)
            return "First Floor";
        if ($no == 3)
            return "Second Floor";
        return $no . ' Floor';
    }

    public static function getBuildingName($id)
    {
        if ($id == 1)
            return "Nikunj";
        if ($id == 2)
            return "Ketan";
        if ($id == 3)
            return "Niket";
        if ($id == 4)
            return "Nilay";
        if ($id == 5)
            return "Basera";
        return 'No Selected';
    }

    public static function getRoomNo()
    {
        $rooms = Room::all();
        $room_list = [];

        foreach ($rooms as $room) {
            $room_list[$room->id] = $room->room_number;
        }

        return $room_list;
    }

    public static function customValidate($request)
    {
        $rooms = $request->get('rooms');

        $room_numbers = [];
        for ($i = 1; $i <= $rooms; $i++) {
            $room_numbers[] = $request->get('room_number_' . $i);
        }
        $ok = true;
        $already_exists = [];
        foreach ($room_numbers as $room_number) {
            $room = Room::where([
                'room_number' => $room_number,
                'building_id' => $request->get('select_building'),
                'floor_number' => $request->get('select_floor_number'),
            ])->first();
            if ($room != null) {
                $already_exists[] = $room_number;
                $ok = false;
            }
        }

        if ($ok == false) {
            $already_exists = implode(',', $already_exists);
            return "Room no " . $already_exists . ' already exists, please choose another room numbers';
        }

        return false;
    }

    public static function getGenderOptions($id = null)
    {
        $list = [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male',
            self::GENDER_NA => 'NA'
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }

    public static function getRowClass($data, $beds, $code = false)
    {
        if ($data['is_blocked'] == true) {
            if ($code == true)
                return "BL";
            return "is_blocked";
        }

        if ($data['room_status'] == false) {
            if ($code == true)
                return "V";
            return 'fully_vacant';
        } else {
            if ($data['is_fully_booked'] == true) {
                if ($code == true)
                    return "B";
                return "fully_booked";
            }

            if ($data['is_extra_bed'] == true) {
                if ($code == true)
                    return "EB";
                return "extra_bed";
            }
            for ($i = 1; $i <= $beds; $i++) {
                if ($data['b' . $i . '_status'] == false) {
                    if ($code == true)
                        return "PB";
                    return "partially_filled";
                }
            }
        }
        if ($code == true)
            return "PB";
        return "partially_filled";
    }

    public static function getBedClass($data, $bed, $code = false)
    {
        if ($data['is_blocked'] == true) {
            if ($code == true)
                return "BL";
            return "is_blocked";
        }

        if ($data['room_status'] == false) {
            if ($code == true)
                return "V";
            return 'vacant';
        } else {
            if ($data['single_occupancy'] == true) {
                if ($code == true)
                    return "SO";
                return "single_occupancy";
            }

            if ($data['b' . $bed . '_status'] == true) {
                if ($code == true)
                    return "B";
                return "booked";
            }
        }
        if ($code == true)
            return "V";
        return "vacant";
    }

    public static function getBedOptionsArray($room_id, $check_in_date, $check_out_date, $gender, $booking_id, $member = false)
    {

        $room = Room::find($room_id);

        $dates = self::dateRange($check_in_date, $check_out_date);

        $ok = true;
        $b_count = 0;
        $booked_bed_status = [];
         $bed_status_ar = [
            'all_booked' => Room::IS_AVAILABLE
        ];
         if ($room) {
        foreach ($dates as $date) {
            $b_count = 0;
            $booking_room_query = BookingRoom::where([
                'room_id' => $room->id
            ])->where(function ($query) use ($date) {
                $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
            });

            $booking_room = $booking_room_query->where('type', Booking::BOOKING_TYPE_SINGLE_OCCUPANCY)->where('booking_id', '!=', $booking_id)->whereNull('member_id')->first();
            if ($member == true) {
                $booking_room = $booking_room_query->where('type', Booking::BOOKING_TYPE_SINGLE_OCCUPANCY)->where('member_id', '!=', $booking_id)->first();
            }

            $bed_status_ar = [
                'all_booked' => Room::IS_AVAILABLE,
            ];

            if ($booking_room != null) {
                $ok = false;
                break;
            } else {
                $b = BookingRoom::where([
                    'room_id' => $room->id
                ])->where(function ($query) use ($date) {
                    $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
                })->where(['type' => Booking::BOOKING_TYPE_SINGLE_BED]);
                $bed_bookings = $b->get();

                foreach ($bed_bookings as $bed_booking) {
                    $booked_bed_status[$bed_booking->bed_number]['status'] = Room::IS_BLOCKED;
                    $booked_bed_status[$bed_booking->bed_number]['booked_by_me'] = false;
                    if ($member == true) {
                        if ($bed_booking->member_id == $booking_id) {
                            $booked_bed_status[$bed_booking->bed_number]['status'] = Room::IS_AVAILABLE;
                            $booked_bed_status[$bed_booking->bed_number]['booked_by_me'] = true;
                        }
                    } else {
                        if ($bed_booking->booking_id == $booking_id && $bed_booking->member_id == null) {
                            $booked_bed_status[$bed_booking->bed_number]['status'] = Room::IS_AVAILABLE;
                            $booked_bed_status[$bed_booking->bed_number]['booked_by_me'] = true;
                        }
                    }
                }
                $bed_count = $b->count();
                // echo '<pre>'; print_r($booking_room);

                /*$b_count = $b_count + $bed_count;
                if ($b_count == $room->bed_count)
                    break;*/

                /*
                if ($type == Booking::BOOKING_TYPE_SINGLE_BED) {
                    if ($b == 2) {
                        $ok = false;
                        break;
                    }
                }elseif($b > 0) {
                    $ok = false;
                    break;
                }*/
            }
        }
         
       

        if ($ok == true) {
            if (in_array($room->gender, [Room::GENDER_NA, $gender])) {
                $beds = $room->bed_count;
                for ($i = 1; $i <= $beds; $i++) {
                    $bed_ar['bed_no'] = $i;
                    $bed_ar['bed_status'] = isset($booked_bed_status[$i]['status']) ? $booked_bed_status[$i]['status'] : Room::IS_AVAILABLE;
                    $bed_ar['booked_by_me'] = isset($booked_bed_status[$i]['booked_by_me']) ? $booked_bed_status[$i]['booked_by_me'] : false;
                    $bed_status_ar['beds'][] = $bed_ar;
                }
            }
        } else {
            $bed_status_ar = [
                'all_booked' => Room::IS_BLOCKED
            ];
        }
    }

        return $bed_status_ar;
    }

    public function building()
    {
        return $this->belongsTo('App\Building', 'building_id');
    }

    public function roomType()
    {
        return $this->belongsTo('App\Room_Type', 'room_type_id');
    }

    public function bookedRooms()
    {
        return $this->hasMany('App\BookingRoom', 'room_id');
    }

    public function getRules()
    {
        $rules = [
            'room_number_1' => 'required|max:255',
            'room_type' => 'required|numeric',
            'gender' => 'required|numeric',
            'select_building' => 'required|numeric',
            'select_floor_number' => 'required|numeric',
        ];

        return $rules;
    }

    public function getBookingTypes()
    {
        if ($this->bed_type == self::BED_TYPE_DOUBLE) {
            return Booking::getDoubleBedTypes();
        } else {
            return Booking::getSingleBedTypes();
        }
    }

    public function saveRooms($request)
    {
        $rooms = $request->get('rooms');

        $room_numbers = [];
        for ($i = 1; $i <= $rooms; $i++) {
            $room_numbers[] = $request->get('room_number_' . $i);
        }
        $ok = true;
        $already_exists = [];
        foreach ($room_numbers as $room_number) {
            $room = Room::where([
                'room_number' => $room_number,
                'building_id' => $request->get('select_building'),
            ])->first();
            if ($room == null) {
                Room::create([
                    'room_number' => $room_number,
                    'building_id' => $request->get('select_building'),
                    'floor_number' => $request->get('select_floor_number'),
                    'room_type_id' => $request->get('room_type'),
                    'gender' => $request->get('gender'),
                    'bed_count' => $request->get('bed_count'),
                    'bed_price' => $request->get('bed_price'),
                    'room_price' => $request->get('room_price'),

                    'services' => is_array($request->get('services')) ? implode(',', $request->get('services')) : ""
                ]);
            }
        }

        return true;
    }

    public function isBlocked($month = null)
    {
        $blocked_room = BlockedRoom::where('room_id', $this->id)->first();
        if ($blocked_room != null) {
            if ($blocked_room->is_yearly == BlockedRoom::BLOCK_YEAR) {
                return true;
            }

            if ($month != null) {
                $month_ar = array_filter(explode(',', $blocked_room->blocked_months));
                if (in_array($month, $month_ar)) {
                    return true;
                }
            }
        }/*
        if ($this->is_blocked == self::IS_BLOCKED) {
            return true;
        }*/
        return false;
    }

    public function serviceChecked($id)
    {
        $services = explode(',', $this->services);

        if (in_array($id, $services)) {
            return true;
        }

        return false;
    }

    public function getServices()
    {
        $services = explode(',', $this->services);
        $ext_services = ExternalService::whereIn('id', $services)->get();
        return $ext_services;/*
        $ext_ser_ar = [];
        foreach ($ext_services as $ext_service) {
            $ext_ser_ar[$ext_service->id] = $ext_service->name;
        }
        return $ext_ser_ar;*/
    }

    public function checkBooking($check_in_date, $check_out_date, $type, $gender = UserProfile::GENDER_NOT_SPECIFIED, $booking_id = null, $member = false, $room_id = null)
    {
        $dates = $this->dateRange($check_in_date, $check_out_date);
        $ok = true;
        $b_count = 0;
        $bed_booking_status = [];

        foreach ($dates as $date) {
            $b_count = 0;
            $booking_room_query = BookingRoom::where([
                'room_id' => $this->id
            ])->where(function ($query) use ($date) {
                $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
            })->where('id', '!=', $room_id);

            $booking_room = $booking_room_query->where('type', Booking::BOOKING_TYPE_SINGLE_OCCUPANCY)->where('booking_id', '!=', $booking_id)->whereNull('member_id')->first();

            /*if ($member == true) {
                $booking_room = BookingRoom::where([
                    'room_id' => $this->id
                ])->where(function ($query) use ($date) {
                    $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
                })->where([
                    'type' => Booking::BOOKING_TYPE_SINGLE_OCCUPANCY])->where(function ($query) use ($booking_id) {
                    $query->where(\DB::raw('member_id != ' . $booking_id))->orWhereNull('member_id');
                })->first();
            }
*/
            if ($member == true) {
                $booking_room = BookingRoom::where([
                    'room_id' => $this->id
                ])->where(function ($query) use ($date) {
                    $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
                })->where([
                    'type' => Booking::BOOKING_TYPE_SINGLE_OCCUPANCY])->first();
            }
            else{
                $booking_room_query = BookingRoom::where([
                'room_id' => $this->id
                ])->where(function ($query) use ($date) {
                    $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
                })->where('id', '!=', $room_id);

                $booking_room = $booking_room_query->where('type', Booking::BOOKING_TYPE_SINGLE_OCCUPANCY)->first();
            }


            if ($booking_room != null) {
                $ok = false;
                break;
            } else {
                $b = BookingRoom::with(BookingRoom::getRelationsList())->where([
                    'room_id' => $this->id
                ])->where(function ($query) use ($date) {
                    $query->where('check_in_date', '<=', $date)->where('check_out_date', '>=', $date);
                })->where(['type' => Booking::BOOKING_TYPE_SINGLE_BED]);

                $bed_bookings = $b->get();
                $bed_count = $b->count();


                foreach ($bed_bookings as $bed_booking) {
                    $booked_gender = $bed_booking->booking->userProfile->gender;
                    if ($bed_booking->member_id != null) {
                        $booked_gender = $bed_booking->member->gender;
                    }

                    if ($member == true) {
                        if ($bed_booking->member_id != $booking_id) {
                            $b_count++;
                        }
                    } else {
                        if ($bed_booking->booking_id != $booking_id) {
                            $b_count++;
                        }
                    }                   
                }
                if ($b_count == $this->bed_count)
                    break;

                /*
                // echo '<pre>'; print_r($booking_room);
                 if ($type == Booking::BOOKING_TYPE_SINGLE_BED) {
                     if ($b == 2) {
                         $ok = false;
                         break;
                     }
                 }elseif($b > 0) {
                     $ok = false;
                     break;
                 }*/
            }
        }




        if ($ok == true) {
//            if (in_array($this->gender, [Room::GENDER_NA, $gender])) {
                $beds = $this->bed_count;
                if ($type == BookingRoom::BOOKING_TYPE_SINGLE_OCCUPANCY) {
                    if ($b_count > 0) {
                        $ok = false;
                    } elseif ($beds <= $b_count) {
                        $ok = false;
                    }
                } elseif ($beds <= $b_count) {
                    $ok = false;
                }
            } else {
                $ok = false;
            }
//      }

        return $ok;
    }

    protected static function dateRange($date, $end_date)
    {
        $dates = array();

        $current_date = strtotime($date);
        $max_date = min(strtotime('+2 years'), strtotime($end_date));

        while ($current_date < $max_date) {
            $current_date = strtotime('+1 day', $current_date);
            $dates[] = date('Y-m-d', $current_date);
        }

        return $dates;
    }

    public function getRoomType()
    {
        return $this->roomType->short_name;
    }

    public function customDelete()
    {
        $booked_rooms = $this->bookedRooms;

        foreach ($booked_rooms as $booked_room) {
            $booked_room->customDelete();
        }

        $this->delete();
    }

    public function checkDelete()
    {
        return $this->bookedRooms->count();
    }
}
