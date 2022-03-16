<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\IFTTTHandler;

class Booking extends Model
{
    const BOOKING_TYPE_SINGLE_BED = 0;
    const BOOKING_TYPE_SINGLE_OCCUPANCY = 1;
    const BOOKING_TYPE_SINGLE_OCCUPANCY_EB = 2;

    const BOOKING_TYPE_DOUBLE_BED = 3;
    const BOOKING_TYPE_DOUBLE_BED_EB = 4;
    const BOOKING_TYPE_DOUBLE_BED_SHARING = 5;

    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_DISCHARGED = 4;

    const PRINT_NOC = 1;

    const PATIENT_TYPE_IPD = 1;
    const PATIENT_TYPE_OPD = 2;

    const ACCOMMODATION_STATUS_PENDING = 1;
    const ACCOMMODATION_STATUS_CONFIRMED = 2;
    protected $table = 'bookings';


    protected $fillable = [
        'user_id',
        'room_id',
        'booking_type',
        'check_in_date',
        'check_out_date',
        'building_id',
        'floor_number',
        'is_confirmed',
        'status',
        'patient_type',
        'booking_id',
        'is_servant',
        'is_driver',
        'is_child',
        'child_count',
        'driver_count',
        'accommodation_status',
        'cancel_reason',
        'external_services',
        'booking_kid'
    ];

    protected $appends = [
        'building_name',
        'building_floor_name',
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

    public function getBuildingNameAttribute()
    {
        return $this->building ? $this->building->name : '';
    }

    public function getBuildingFloorNameAttribute()
    {
        return Room::getFloorNumber($this->floor_number);
    }

    public static function getSingleBedTypesCheckboxes($single = false)
    {
        $data = self::getSingleBedTypes($single);
        $checkboxes = "";
        foreach ($data as $booking_type_id => $booking_type_value) {
            $checkboxes .= '<input checked type="radio" name="booking_type" value=' . $booking_type_id . ' class = "booking_type_id" required> ' . $booking_type_value . '<br>';
        }
        return $checkboxes;
    }

    public static function getSingleBedTypes($single = false)
    {
        $list = [
            self::BOOKING_TYPE_SINGLE_BED => 'Single Bed'
        ];
        if ($single == false) {
            $list[self::BOOKING_TYPE_SINGLE_OCCUPANCY_EB] = 'Single Occupancy with Extra Bed';
            $list[self::BOOKING_TYPE_SINGLE_OCCUPANCY] = 'Single Occupancy';
        }
        return $list;
    }

    public static function getDoubleBedTypesCheckboxes($single = false)
    {
        $data = self::getDoubleBedTypes($single);
        $checkboxes = "";
        foreach ($data as $booking_type_id => $booking_type_value) {
            $checkboxes .= '<input checked type="radio" name="booking_type" value=' . $booking_type_id . ' class = "booking_type_id" required> ' . $booking_type_value . '<br>';
        }
        return $checkboxes;
    }

    public static function getDoubleBedTypes($single = false)
    {
        $list = [
            self::BOOKING_TYPE_DOUBLE_BED_SHARING => 'Double Bed with sharing',
        ];

        if ($single == false) {
            $list[self::BOOKING_TYPE_DOUBLE_BED] = 'Double Bed';
            $list[self::BOOKING_TYPE_DOUBLE_BED_EB] = 'Double Bed with Extra Bed';
        }

        return $list;
    }

    public static function getBookingType($id = null)
    {
        $list = [
            self::BOOKING_TYPE_SINGLE_BED => 'Single Bed',
            self::BOOKING_TYPE_SINGLE_OCCUPANCY_EB => 'Single Bed with Extra Bed',
            self::BOOKING_TYPE_SINGLE_OCCUPANCY => 'Single Occupancy',
            self::BOOKING_TYPE_DOUBLE_BED => 'Double Bed',
            self::BOOKING_TYPE_DOUBLE_BED_SHARING => 'Double Bed with sharing',
            self::BOOKING_TYPE_DOUBLE_BED_EB => 'Double Bed with Extra Bed',
        ];
        if ($id === null)
            return $list;

        return $list[$id];
    }

    public static function overallMonth($month_year = null)
    {
        if ($month_year == null) {
            $month_year = date("m") . '-' . date("Y");
        }
        $month = date("M", strtotime($month_year));
        $month_year = explode('-', $month_year);
        $b_ids = BlockedRoom::getRoomIds($month);
        $bookings = BookingRoom::whereNull('status')
            ->where(function ($query) use ($month_year) {
                $query->where(function ($query) use ($month_year) {
                    $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                })->orWhere(function ($query) use ($month_year) {
                    $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                });
            })->whereIn('type', [BookingRoom::BOOKING_TYPE_SINGLE_OCCUPANCY, BookingRoom::BOOKING_TYPE_EXTRA_BED])->get();
        $booked_ids = [];
        foreach ($bookings as $booking) {
            $booked_ids[] = $booking->room_id;
        }
        $not_in = array_merge($b_ids, $booked_ids);

        $overall = [];

        foreach (Room_Type::all() as $room_type) {
            $rooms = Room::whereNotIn('id', $not_in)->where('room_type_id', $room_type->id)->get();
            $total_rooms = Room::where('room_type_id', $room_type->id)->count();
            $overall[$room_type->id] = [
                'short_name' => $room_type->short_name,
                'available' => 0,
                'male' => 0,
                'female' => 0,
                'total' => $total_rooms * 2,
                'rooms' => $rooms->count()
            ];
            foreach ($rooms as $room) {
                $bookings = BookingRoom::whereNull('status')->where('room_id', $room->id)
                    ->where(function ($query) use ($month_year) {
                        $query->where(function ($query) use ($month_year) {
                            $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                        })->orWhere(function ($query) use ($month_year) {
                            $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                        });
                    })->where('type', BookingRoom::BOOKING_TYPE_SINGLE_BED)->get();

                if ($bookings->count() < 1) {
                    $overall[$room_type->id]['available'] = $overall[$room_type->id]['available'] + 2;
                    $overall[$room_type->id]['male']++;
                    $overall[$room_type->id]['female']++;
                } elseif ($bookings->count() < 2) {
                    $overall[$room_type->id]['available']++;

                    foreach ($bookings as $booking) {
                        $gender = $booking->booking->user->userProfile->gender;
                        if ($booking->member != null) {
                            $gender = $booking->member->gender;
                        }
                        if ($gender == UserProfile::GENDER_FEMALE) {
                            $overall[$room_type->id]['female']++;
                        } else {
                            $overall[$room_type->id]['male']++;
                        }
                    }
                }

            }

        }
        return $overall;
    }

    public static function overall($default_date = null)
    {
        if ($default_date == null) {
            $default_date = date("Y-m-d");
        }
        $month = date("M", strtotime($default_date));
        $b_ids = BlockedRoom::getRoomIds($month);
        $bookings = BookingRoom::whereNull('status')
            ->where(function ($query) use ($default_date) {
                $query->where('check_in_date', '<=', (string)$default_date)->where('check_out_date', '>=', (string)$default_date);
            })->whereIn('type', [BookingRoom::BOOKING_TYPE_SINGLE_OCCUPANCY, BookingRoom::BOOKING_TYPE_EXTRA_BED])->get();

        $booked_ids = [];

        foreach ($bookings as $booking) {
            $booked_ids[] = $booking->room_id;
        }
        $not_in = array_merge($b_ids, $booked_ids);

        $overall = [];

        foreach (Room_Type::all() as $room_type) {
            $rooms = Room::whereNotIn('id', $not_in)->where('room_type_id', $room_type->id)->get();
            $total_rooms = Room::where('room_type_id', $room_type->id)->count();
            $overall[$room_type->id] = [
                'room_id' => "",
                'short_name' => $room_type->short_name,
                'available' => 0,
                'male' => 0,
                'female' => 0,
                'total' => $total_rooms * 2,
                'rooms' => $rooms->count()
            ];
            foreach ($rooms as $room) {
                $overall[$room_type->id]['room_id'] = $room->id;
                $bookings = BookingRoom::whereNull('status')->where('room_id', $room->id)
                    ->where(function ($query) use ($default_date) {
                        $query->where('check_in_date', '<=', (string)$default_date)->where('check_out_date', '>=', (string)$default_date);
                    })->where('type', BookingRoom::BOOKING_TYPE_SINGLE_BED)->get();

                if ($bookings->count() < 1) {
                    $overall[$room_type->id]['available'] = $overall[$room_type->id]['available'] + 2;
                    $overall[$room_type->id]['male']++;
                    $overall[$room_type->id]['female']++;
                } elseif ($bookings->count() < 2) {
                    $overall[$room_type->id]['available']++;

                    foreach ($bookings as $booking) {
                        $gender = $booking->booking->user->userProfile->gender;
                        if ($booking->member != null) {
                            $gender = $booking->member->gender;
                        }
                        if ($gender == UserProfile::GENDER_FEMALE) {
                            $overall[$room_type->id]['female']++;
                        } else {
                            $overall[$room_type->id]['male']++;
                        }
                    }
                }

            }

        }
        return $overall;
    }

    public static function getBookingsChartMonth($room_id, $month_year = null)
    {
        if ($month_year == null) {
            $month_year = date("m") . '-' . date("Y");
        }

        $month_year_arr = explode('-', $month_year);
        $month = $month_year_arr[0];
        $date = $month . ' ' . date("d, Y");
        $month_year_month = date('m', strtotime($date));
        $year = $month_year_arr[1];
        $month_year = [$month_year_month, $year];

        $room = Room::find($room_id);
        $blocked = BlockedRoom::where('room_id', $room->id)->where("blocked_months", "LIKE", "%" . $month . "%")->exists();
        $room_data = [];
        $month_days_count = $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month_year[0], $month_year[1]);

        for ($month_day = 1; $month_day <= $month_days_count; $month_day++) {
            $month_day = sprintf("%02d", $month_day);
            $date = date("Y-m-d", strtotime($month_year[1] . '-' . $month_year[0] . '-' . $month_day));
            $room_data[$date] = [
                'room_id' => $room->id,
                'room_status' => false,
                'is_blocked' => $blocked,
                'is_fully_booked' => false,
                'single_occupancy' => false,
                'b1_status' => false,
                'b2_status' => false,
                'is_extra_bed' => false,
            ];
            if ($blocked == false) {
                $data = self::getBookingsChart($room->id, $date);
                $room_data[$date] = $data;
            }
        }
        return $room_data;
    }

    public static function getBookingsChart($room_id, $default_date = null)
    {
        if ($default_date == null) {
            $default_date = date("Y-m-d");
        }
        else{
            $default_date =  date("Y-m-d", strtotime($default_date));
        }
        //echo  '2019-10-07';
       // return $default_date;
        $month = date("M", strtotime($default_date));

        $room = Room::find($room_id);
        $bookings = BookingRoom::where('room_id', $room->id)
            ->whereNull('status')
            ->where('check_in_date', '<=', $default_date)->where('check_out_date', '>=', $default_date)->get();

        $blocked = BlockedRoom::where('room_id', $room->id)->where("blocked_months", "LIKE", "%" . $month . "%")->exists();
        $room_data = [
            'room_id' => $room->id,
            'room_status' => false,
            'is_blocked' => $blocked,
            'is_fully_booked' => false,
            'single_occupancy' => false,
            'is_extra_bed' => false,
        ];
        for ($i = 1; $i <= $room->bed_count; $i++) {
            $room_data['b' . $i . '_status'] = false;
        }

        if ($blocked == false) {
            if ($bookings->count() > 0) {
                $room_data['room_status'] = true;
                foreach ($bookings as $booking) {
                    if ($booking->type == BookingRoom::BOOKING_TYPE_SINGLE_OCCUPANCY) {
                        $room_data['single_occupancy'] = true;
                    } elseif ($booking->type == BookingRoom::BOOKING_TYPE_EXTRA_BED) {
                        $room_data['is_extra_bed'] = true;
                    } else {
                        $room_data['b' . $booking->bed_number . '_status'] = true;
                        if ($room_data['b' . $room->bed_count . '_status'] == true) {
                            $room_data['is_fully_booked'] = true;
                        }
                    }
                }
            }
        }

        return $room_data;
    }

    public static function guestBookingChartmw($request, $default_month_year = null)
    {
        $accordian_status_mw = 0;
        if (!empty($request->all()) || $default_month_year != null) {
            if (isset($request->select_month_year) && !empty($request->select_month_year)) {
                $default_month_year = $request->select_month_year;
            }
            $month_year_arr = explode('-', $default_month_year);
            $month = $month_year_arr[0];
            $date = $month . ' ' . date("d, Y");
            $month = date('m', strtotime($date));
            $year = $month_year_arr[1];
            $accordian_status_mw = 1;
        } else {
            $month = (int)date('m');
            $year = (int)date('Y');
        }
        $month = (int)$month;
        $room_status_arr = [];
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month, (int)$year);
        $rooms_status_arr['month_data'] = ['month_date_count' => $month_days_count, 'year' => $year, 'month' => $month];
        $rooms = \DB::table('rooms')
            ->join('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where('rooms.status', 1)
            ->select('rooms.*', 'room_types.short_name', 'buildings.name as building_name')
            ->where(function ($query) {
                $query->where('rooms.is_blocked', Room::IS_AVAILABLE)->orWhereNull('rooms.is_blocked');
            })
            ->get();
        if (!$rooms->isempty()) {
            foreach ($rooms as $room) {
                for ($month_day = 1; $month_day <= $month_days_count; $month_day++) {
                    $month_day = sprintf("%02d", $month_day);

                    $room_id = $room->id;
                    $room_status_arr['building'] = $room->building_name;
                    $room_status_arr['room_type'] = $room->short_name;
                    $room_status_arr['room_id'] = $room->id;
                    $room_status_arr['room_number'] = $room->room_number;
                    $room_status_arr['days_bookings'][$month_day] = self::getRoomMonthStatus($room_id, $month_day, $month, $year);
                }
                $rooms_status_arr['rooms_data']['roomm-' . $room->id] = $room_status_arr;
            }
        }
        //echo '<pre>';print_r($rooms_status_arr); die;
        //$data['rooms_status_arr'] = $rooms_status_arr;
        $month_wise_arr = ['accordian_status_mw' => $accordian_status_mw, 'rooms_status_arr' => $rooms_status_arr];
        return $month_wise_arr;
        //return view('booking.booking_chart_month_wise',$data);
    }

    private static function getRoomMonthStatus($room_id, $month_day, $month, $year)
    {
        $booked_room_arr = [];
        /*\DB::enableQueryLog();
        dd(\DB::getQueryLog());*/
        //$month_days_timestamp = strtotime($month_day.'-' . $month . '-' . $year);
        $month_days_date = date($year . '-' . $month . '-' . $month_day . ' ' . '00:00:00');
        $booking_arr = BookingRoom::where('room_id', '=', $room_id)
            ->whereDate('check_in_date', '<=', $month_days_date)
            ->whereDate('check_out_date', '>=', $month_days_date)
            ->whereNotIn('status', [BookingRoom::STATUS_DISCHARGED])
            ->get();
        if (!$booking_arr->isempty()) {
            foreach ($booking_arr as $booking) {
                $user_profile_obj = UserProfile::where('user_id', $booking->booking->user_id)->first();
                $gender = $booking->booking->userProfile->gender;
                if ($booking->member != null)
                    $gender = $booking->member->gender;

                $booked_room_arr[] = ['user_gender' => $gender, 'booking_id' => $booking->booking->id, 'booking_type' => $booking->type, 'user_id' => $booking->booking->user_id];
            }
        }

        /* $members_arr = Member::where('room_id', '=', $room_id)
             ->whereDate('check_in_date', '<=', $month_days_date)
             ->whereDate('check_out_date', '>=', $month_days_date)
             ->whereNotIn('status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED])
             ->get();
         if (!$members_arr->isempty()) {
             foreach ($members_arr as $member) {
                 $booked_room_arr[] = ['user_gender' => $member->gender, 'booking_id' => $member->booking->id, 'booking_type' => $member->booking->booking_type, 'user_id' => $member->booking->user_id];
             }
         }*/
        //
        //echo '<pre>sssss';print_r($booked_room_arr); die;
        return $booked_room_arr;
    }

    public static function guestBookingChart($request, $default_date = null)
    {
        $accordian_status_rw = 0;
        if (!empty($request->all()) || $default_date != null) {
            if (isset($request->select_date) && !empty($request->select_date)) {
                $default_date = $request->select_date;
            }
            $select_date_formatted = date($default_date);
            $select_date_formatted = date('Y-m-d 00:00:00', strtotime($default_date));//time();
            $accordian_status_rw = 1;
        } else {
            $select_date_formatted = date('Y-m-d 00:00:00');//time();
            $default_date = date('Y-m-d');
        }

        $room_id_arr = [];
        $rooms_share_avail = [];
        $rooms_share_gender = [];
        $vacant_room_arr = [];
        $total_partial_vacant = [];
        $single_occupancy_booked = [];
        $single_bed_booked = [];
        $doublebed_shared_booked = [];
        $room_number_iterate = [];
        $room_booked_arr = [];
        $room_arr = [];
        /*\DB::enableQueryLog();
        dd(\DB::getQueryLog());*/


        $booked_room_arr = [];
        /*\DB::enableQueryLog();
        dd(\DB::getQueryLog());*/
        //$month_days_timestamp = strtotime($month_day.'-' . $month . '-' . $year);

        $booking_arr = BookingRoom::whereDate('check_in_date', '<=', $select_date_formatted)
            ->whereDate('check_out_date', '>=', $select_date_formatted)
            ->whereNotIn('status', [BookingRoom::STATUS_DISCHARGED])
            ->get();
        $booked_rooms_ids = [];
        if (!$booking_arr->isempty()) {
            foreach ($booking_arr as $booking) {
                if ($booking->room != null) {
                    $booked_rooms_ids[] = $booking->room_id;

                    $gender = $booking->booking->userProfile->gender;
                    if ($booking->member != null)
                        $gender = $booking->member->gender;

                    $room_booked_arr[$booking->room_id][] = ['booking_type' => $booking->type, 'booking_gender' => $gender, 'booking_id' => $booking->booking_id];
                    $room_id_arr[$booking->room_id] = $booking->room_id;
                    //$two_user_booked_signle_room[$bk_room->room_type_id][$bk_room->room_number][] = user_uid;
                    if (in_array($booking->room->room_number, $room_number_iterate)) {
                        unset($rooms_share_avail[$booking->room->room_type_id][$booking->room->room_number]);
                    } else {
                        // 1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing
                        if ($booking->type != 2 && $booking->type != 3) {
                            if ($gender == 1) {
                                $gender = 'female';
                            } else {
                                $gender = 'male';
                            }
                            $rooms_share_avail[$booking->room->room_type_id][$booking->room->room_number][] = $gender;
                        }
                    }
                    $room_number_iterate[] = $booking->room->room_number;
                }
            }
        }

        /*$members_arr = Member::whereNotNull('room_id')
            ->whereDate('check_in_date', '<=', $select_date_formatted)
            ->whereDate('check_out_date', '>=', $select_date_formatted)
            ->whereNotIn('status', [Booking::STATUS_DISCHARGED, Booking::STATUS_CANCELLED])
            ->get();

        if (!$members_arr->isempty()) {
            foreach ($members_arr as $member) {
                $room_booked_arr[$member->room_id][] = ['booking_type' => $member->booking_type, 'booking_gender' => $member->gender, 'booking_id' => $member->booking_id];
                $room_id_arr[$member->room_id] = $member->room_id;
                //$two_user_booked_signle_room[$bk_room->room_type_id][$bk_room->room_number][] = user_uid;
                if (in_array($member->room->room_number, $room_number_iterate)) {
                    unset($rooms_share_avail[$member->room->room_type_id][$member->room->room_number]);
                } else {
                    // 1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing
                    if ($member->room->booking_type != 2 && $member->room->booking_type != 3) {
                        if ($member->gender == 1) {
                            $gender = 'female';
                        } else {
                            $gender = 'male';
                        }
                        $rooms_share_avail[$member->room->room_type_id][$member->room->room_number][] = $gender;
                    }
                }
                $room_number_iterate[] = $member->room->room_number;
            }
        }*/


        /*

        foreach ($booked_rooms as $bk_room){
            // create booking array based on room id
            $room_booked_arr[$bk_room->room_id][] = ['booking_type' => $bk_room->booking_type,'booking_gender' => $bk_room->gender,'booking_id'=>$bk_room->booking_id];
            $room_id_arr[$bk_room->id] = $bk_room->id;
            //$two_user_booked_signle_room[$bk_room->room_type_id][$bk_room->room_number][] = user_uid;
            if(in_array($bk_room->room_number, $room_number_iterate)){
                unset($rooms_share_avail[$bk_room->room_type_id][$bk_room->room_number]);
            }else {
                // 1 => SingleBed, 2 => SingleOccupancy, 3=>SingleOccupancyWithExtraBed, 4- DoubleBedWithSharing
                if ($bk_room->booking_type != 2 && $bk_room->booking_type != 3) {
                    if ($bk_room->gender == 1) {
                        $gender = 'female';
                    } else {
                        $gender = 'male';
                    }
                    $rooms_share_avail[$bk_room->room_type_id][$bk_room->room_number][] = $gender;
                }
            }
            $room_number_iterate[] = $bk_room->room_number;
        }*/
        //echo '<pre>'; print_r($rooms_share_avail); echo '</pre>'; die;
        /*$array = array("Kyle","Ben","Sue","Phil","Ben","Mary","Sue","Ben");
        $counts = array_count_values($array);
        echo $counts['Ben'];*/
        // get gender based booking number
        foreach ($rooms_share_avail as $room_type => $room_share_avail) {
            foreach ($room_share_avail as $room_number => $gender) {
                $counts = array_count_values($gender);
                if (isset($counts['female'])) {
                    $rooms_share_gender[$room_type]['female_count'] = $counts['female'];
                } else {
                    $rooms_share_gender[$room_type]['female_count'] = 0;
                }
                if (isset($counts['male'])) {
                    $rooms_share_gender[$room_type]['male_count'] = $counts['male'];
                } else {
                    $rooms_share_gender[$room_type]['male_count'] = 0;
                }
            }
        }
        $month = date("M", strtotime($select_date_formatted));
        $b_ids = BlockedRoom::getRoomIds($month);

        $not_in_ids = array_merge($b_ids, $booked_rooms_ids);

        $vacant_rooms = \DB::table('rooms')
            ->whereNotIn('rooms.id', $not_in_ids)
            ->Join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.id as room_type_id')
            ->get();
        foreach ($vacant_rooms as $vacant_room) {
            $vacant_room_arr[$vacant_room->room_type_id][] = $vacant_room->room_number;
        }
        //echo '<pre>'; print_r($vacant_rooms); echo '</pre>'; die;
        $room_types = Room_Type::all();
        foreach ($room_types as $room_type) {
            $total_vacant_rooms = 0;
            $total_partial_rooms = 0;
            $female_count = 0;
            $male_count = 0;
            $any_gender = 0;
            $single_occupancy_rooms = 0;
            // Get total rooms
            if (isset($vacant_room_arr[$room_type->id])) {
                $total_vacant_rooms = count($vacant_room_arr[$room_type->id]);
                // double vacant room as two bed is available for booking in each room
                $total_vacant_rooms = 2 * $total_vacant_rooms;
            }
            // get total sharing booking
            if (isset($rooms_share_gender[$room_type->id])) {
                $female_count = $rooms_share_gender[$room_type->id]['female_count'];
                $male_count = $rooms_share_gender[$room_type->id]['male_count'];
                $total_partial_rooms = $female_count + $male_count;
            }
            // get total single occupancy booking
            if (isset($single_occupancy_booked[$room_type->id])) {
                $single_occupancy_rooms = count($single_occupancy_booked[$room_type->id]);
            }
            // double the single occupancy rooms as basically two bed are booked as single occupancy;
            $rooms = Room::where('room_type_id', $room_type->id)->where(function ($query) {
                $query->where('rooms.is_blocked', Room::IS_AVAILABLE)->orWhereNull('rooms.is_blocked');
            })
                ->where('status', 1);
            $total = $rooms->count();
            // Double th room total because each room has two beds
            $total = 2 * $total;
            $total_vacant_rooms = $total_vacant_rooms + $total_partial_rooms;
            $total_partial_vacant[$room_type->id] = ['room_type' => $room_type->name, 'total' => $total, 'total_vacant_rooms' => $total_vacant_rooms, 'female' => $female_count, 'male' => $male_count];
            $room_types_arr[$room_type->id] = $room_type->name;
        }


        //Room::where('status',1)
        $rooms_obj = \DB::table('rooms')
            ->whereNotIn('rooms.id', $b_ids)
            ->leftjoin('room_types', 'room_types.id', '=', 'rooms.room_type_id')
            ->leftjoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select('rooms.id as room_id', 'rooms.room_number', 'rooms.room_type_id', 'rooms.building_id', 'rooms.floor_number',
                'room_types.id as room_type_id', 'room_types.name as room_type_name', 'room_types.short_name as room_type_short_name',
                'buildings.name as building_name')->get();
        $building_arr = [];
        foreach ($rooms_obj as $room) {
            if (isset($room_booked_arr[$room->room_id])) {
                $booking_status = $room_booked_arr[$room->room_id];
            } else {
                $booking_status = [];
            }
            $building_arr[$room->building_id] = ['building_name' => $room->building_name,
                'room_type_name' => $room->room_type_name];
            $room_data[$room->building_id][] = [
                'room_id' => $room->room_id,
                'room_number' => $room->room_number,
                'booking_status' => $booking_status,
                'room_type_short_name' => $room->room_type_short_name,
                'floor_number' => $room->floor_number
            ];

        }
        foreach ($building_arr as $building_id => $building) {
            $room_arr[$building_id] = ['building_data' => $building, 'room_data' => $room_data[$building_id]];
        }
        $data['total_partial_vacant'] = $total_partial_vacant;
        $data['room_arr'] = $room_arr;
        $data['default_date'] = $default_date;
        $data['accordian_status_rw'] = $accordian_status_rw;
        return $data;
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DISCHARGED => 'Discharged'
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }

    public static function getBookingTypeOptions()
    {
        $list = [
            self::BOOKING_TYPE_SINGLE_OCCUPANCY => 'Book Room',
            /*self::BOOKING_TYPE_SINGLE_OCCUPANCY_EB => 'Book Room with Extra Bed',*/
            self::BOOKING_TYPE_SINGLE_BED => 'Book Bed',
        ];

        return $list;
    }

    public static function getPatientType($id = null)
    {
        $list = [
            self::PATIENT_TYPE_IPD => 'IPD',
            self::PATIENT_TYPE_OPD => 'OPD'
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }

    public static function getRoutesArray($patient = false)
    {
        if ($patient == true) {
            return [
                'Laralum::patient.list',
                'Laralum::doctors.print',
                'Laralum::doctors.export',
                'Laralum::doctors_create',
                'Laralum::doctors_edit',
                'Laralum::users_roles',
            ];
        }
        return [
            'Laralum::admin.booking.pending',
            /*  'Laralum::bookings',*/
            'Laralum::print',
            'Laralum::users.export',
            'Laralum::users_create',
            'Laralum::users_edit',
            'Laralum::users_roles',
            'Laralum::accomodations'
        ];
    }

    public static function getNewBookingArray()
    {
        return [
            'Laralum::booking.registration.create',
            'Laralum::booking.registration.personalDetails',
            'Laralum::booking.registration.health_issues',
            'Laralum::booking.registration.accommodation',
            'Laralum::booking.registration.payment',
            'Laralum::booking.registration.confirm'
        ];
    }

    public static function getListRoutesArray()
    {
        return [
            /*'Laralum::bookings',*/
            'Laralum::booking.print_kid',
            /* 'Laralum::booking.show',
             'Laralum::booking.personalDetails',
             'Laralum::booking.personalDetails.store',
             'Laralum::booking.health_issues',
             'Laralum::booking.health_issues.store',
             'Laralum::booking.accommodation',
             'Laralum::booking.payment',
             'Laralum::booking.confirm',
             'Laralum::booking.print_kid',
             'Laralum::bookings.print_patient_card',
             'Laralum::bookings.print_patient_card',
             'Laralum::booking.info',
             'Laralum::booked.room.info',
             'Laralum::full.booked.room.info',
             'Laralum::bookings.account',*/
        ];
    }

    public static function getPatientTokenArray()
    {
        return [
            'Laralum::token.list',
            'Laralum::tokens.print',
            'Laralum::tokens.export',
            'Laralum::bookings.generate_token',
            'Laralum::booking.generate_token',
            'Laralum::bookings.delete_token',
            'Laralum::tokens.print.token'
        ];
    }

    public static function getTreatmentTokenArray()
    {
        return [
            'Laralum::booking.treatment-tokens',
        ];
    }

    public static function getDischargeBiilingArray()
    {
        return [
            'Laralum::bookings.discharge-patient-billing',
            'Laralum::bookings.discharge-patient-billing-individual'
        ];
    }

    public static function getFolowupArray()
    {
        return [
            'Laralum::bookings.follow-ups',
            'Laralum::bookings.follow-ups.export'
        ];
    }

    public static function getAccomodationArray()
    {
        return [
            'Laralum::accomodations',
        ];
    }


    public static function getArchivedArray()
    {
        return [
            'Laralum::archived.patients.list',
        ];
    }

    public function accommodationStatus()
    {
        if ($this->isCancelled()) {
            return "Cancelled";
        }

        if ($this->isDischarged()) {
            return "Discharged";
        }

        return !empty($this->accommodation_status) ? self::getAccommodationStatusOptions($this->accommodation_status) : self::getAccommodationStatusOptions(\App\Booking::ACCOMMODATION_STATUS_PENDING);
    }

    public function isCancelled()
    {
        if ($this->status == self::STATUS_CANCELLED) {
            return true;
        }
        return false;
    }

    public function isDischarged()
    {
        if ($this->status == self::STATUS_DISCHARGED) {
            return true;
        }
        return false;
    }

    public static function getAccommodationStatusOptions($id = null)
    {
        $list = [
            self::ACCOMMODATION_STATUS_PENDING => 'Pending',
            self::ACCOMMODATION_STATUS_CONFIRMED => 'Confirmed',
        ];
        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public function healthIssues()
    {
        return $this->hasOne('App\HealthIssue', 'booking_id');
    }

    public function recommened_exercises()
    {
        return $this->hasMany(ApplyRecommendExcercise::class, 'booking_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class, 'booking_id');
    }

    public function lastBill() {
        $bill = Bill::orderBy('id', 'desc')->where('booking_id', $this->id)->first();
        return $bill;
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'booking_type' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
            'building_id' => 'required',
            'floor_number' => 'required',
        ];
    }

    public function members()
    {
        return $this->hasMany('App\Member', 'booking_id');
    }

    public function building()
    {
        return $this->belongsTo('App\Building', 'building_id');
    }

    public function userProfile()
    {
        return $this->belongsTo('App\UserProfile', 'profile_id');
    }

    public function address()
    {
        return $this->hasOne('App\UserAddress', 'booking_id');
    }

    public function services()
    {
        return $this->hasMany('App\UserExtraService', 'booking_id');
    }

    public function room()
    {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public function bookingRooms()
    {
        return $this->hasMany('App\BookingRoom', 'booking_id')->whereNull('member_id');
    }

    public function bookingRoomsAll()
    {
        return $this->hasMany('App\BookingRoom', 'booking_id');
    }

    public function discounts()
    {
        return $this->hasMany('App\BookingDiscount', 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function paymentDetail()
    {
        return $this->hasOne('App\PaymentDetail', 'booking_id');
    }

    public function provisional_diagnosis()
    {
        return $this->hasOne(PatientDiagnosis::class, 'booking_id')->where('doctor_id', \Auth::user()->id);
    }

    public function setData($request)
    {
        if ($request->has('is_child')) {
            if ($request->has('child_count') && $request->get('child_count') > 0) {
                $child_count = $request->get('child_count');
            } else {
                $child_count = 1;
            }
        } else {
            $child_count = 0;
        }
        if ($request->has('is_driver')) {
            if ($request->has('driver_count') && $request->get('driver_count') > 0) {
                $driver_count = $request->get('driver_count');
            } else {
                $driver_count = 1;
            }
        } else {
            $driver_count = 0;
        }
        $this->user_id = $request->get('user_id');
        $this->booking_type = $request->get('booking_type');
        $this->check_in_date = date('Y-m-d', strtotime($request->get('check_in_date')));
        $this->check_out_date = date('Y-m-d', strtotime($request->get('check_out_date')));
        $this->building_id = $request->get('building_id');
        $this->floor_number = $request->get('floor_number');
        $this->is_servant = $request->get('is_servant');
        $this->is_driver = $request->get('is_driver');
        $this->is_child = $request->get('is_child');
        $this->child_count = $child_count;
        $this->driver_count = $driver_count;
        $this->external_services = is_array($request->external_services) ? implode(',', $request->external_services) : $request->external_services;

        if ($this->id == null) {
            $this->status = Booking::STATUS_PENDING;
        }

        if ($this->accommodation_status != Booking::ACCOMMODATION_STATUS_CONFIRMED) {
            $this->accommodation_status = Booking::ACCOMMODATION_STATUS_PENDING;
        }
    }

    public function checkBooking()
    {
        $bookings = BookingRoom::where([
            'booking_rooms.status' => self::STATUS_COMPLETED
        ])->join('rooms', 'rooms.id', '=', 'booking_rooms.room_id')
            ->where('rooms.building_id', $this->building_id)
            ->where('rooms.floor_number', $this->floor_number)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('booking_rooms.check_in_date', '<=', $this->check_in_date)->where('booking_rooms.check_out_date', '>=', $this->check_in_date);
                })->orWhere(function ($query) {
                    $query->where('booking_rooms.check_in_date', '<=', $this->check_out_date)->orWhere('booking_rooms.check_out_date', '>=', $this->check_out_date);
                });
            })->get();

        $total = 0;
        $male_single_bookings = 0;
        $male_room_bookings = 0;
        $female_single_bookings = 0;
        $female_room_bookings = 0;
        foreach ($bookings as $booking) {
            if ($booking->booking != null) {
                $total++;
                $gender = UserProfile::GENDER_MALE;
                if ($booking->member_id == null) {
                    $gender = $booking->booking->getProfile('gender_id');
                } else {
                    $gender = $booking->member->gender;
                }

                if ($gender == UserProfile::GENDER_MALE) {
                    if ($booking->type == Booking::BOOKING_TYPE_SINGLE_BED) {
                        $male_single_bookings++;
                    } else {
                        $male_room_bookings++;
                    }
                } else {
                    if ($booking->type == Booking::BOOKING_TYPE_SINGLE_BED) {
                        $female_single_bookings++;
                    } else {
                        $female_room_bookings++;
                    }
                }
            }
        }

        $male_rooms = 0;
        $male_single_bed = false;
        if ($male_single_bookings % 2 == 0) {
            $male_rooms = $male_room_bookings + $male_single_bookings / 2;
        } else {
            $male_single_bookings = $male_single_bookings - 1;
            $male_rooms = $male_room_bookings + $male_single_bookings / 2 + 1;
            $male_single_bed = true;
        }
        \Log::info('$male_rooms' . $male_rooms . '$male_single_bed' . $male_single_bed);

        $female_rooms = 0;
        $female_single_bed = false;
        if ($female_single_bookings % 2 == 0) {
            $female_rooms = $female_room_bookings + $female_single_bookings / 2;
        } else {
            $female_single_bookings = $female_single_bookings - 1;
            $female_rooms = $female_room_bookings + $female_single_bookings / 2 + 1;
            $female_single_bed = true;
        }
        \Log::info('$female_rooms' . $female_rooms . '$female_single_bed' . $female_single_bed);
        $total = $male_room_bookings + $female_room_bookings;
        $total_booked_beds = $male_single_bookings + $female_single_bookings;

        $rooms = Room::where([
            'building_id' => $this->building_id,
            'floor_number' => $this->floor_number,
        ])->where(function ($query) {
            $query->whereNull('is_blocked')->orWhere('is_blocked', Room::IS_AVAILABLE);
        })->get();


        \Log::info('$rooms' . $rooms);
        $na_rooms = 0;
        $m_rooms = 0;
        $f_rooms = 0;

        $na_beds = 0;
        $m_beds = 0;
        $f_beds = 0;
        foreach ($rooms as $room) {
            if ($room->gender == Room::GENDER_NA) {
                $na_rooms++;
                $na_beds = $na_beds + $room->bed_count;
            } elseif ($room->gender == Room::GENDER_MALE) {
                $m_rooms++;
                $m_beds = $m_beds + $room->bed_count;
            } else {
                $f_rooms++;
                $f_beds = $f_beds + $room->bed_count;
            }
        }

        $current_gender = $this->getProfile('gender_id');
        if ($current_gender == UserProfile::GENDER_FEMALE) {

            if ($this->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                $total_beds = $na_beds + $f_beds;
                \Log::info('$fgtotal' . $total . '$total_beds' . $total_beds);
                if ($female_single_bed == true) {
                    $gtotal = $total - 1;
                    if ($total_booked_beds - 1 < $total_beds) {
                        return true;
                    }
                } else {
                    if ($total_booked_beds < $total_beds) {
                        return true;
                    }
                }

            } else {
                $total_rooms = $na_rooms + $f_rooms;
                if ($total < $total_rooms) {
                    return true;
                }
            }
        } else {

            if ($this->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                $total_beds = $na_beds + $m_beds;
                if ($male_single_bed == true) {
                    if ($total_booked_beds - 1 < $total_beds) {
                        return true;
                    }
                } else {
                    if ($total_booked_beds < $total_beds) {
                        return true;
                    }
                }
            } else {
                $total_rooms = $na_rooms + $m_rooms;
                \Log::info('$fgtotal' . $total . '$mtotal_rooms' . $total_rooms);
                if ($total < $total_rooms) {
                    return true;
                }
            }
        }
    }

    public function getProfile($attr = null)
    {
        $profile = $this->userProfile;

        if ($profile != null) {
            if ($attr != null) {
                if ($attr == 'age') {
                    return $profile->getAge();
                }
                if ($attr == 'profession') {
                    return $profile->profession->name;
                }
                if ($attr == 'address') {
                    return $profile->getAddress();
                }
                if ($attr == 'uhid') {
                    return $profile->user->uhid;
                }
                if ($attr == 'profile_picture') {
                    if ($profile->profile_picture != null) {
                        return Settings::getImageUrl($profile->profile_picture);
                    }
                    return false;
                }
                return $profile->$attr;
            }
            return true;
        } else {
            if ($attr == 'uhid') {
                return $this->user->uhid;
            }
            if ($attr == 'first_name') {
                return $this->user->name;
            }
        }
        return false;
    }

    public function checkBookingOld()
    {
        $bookings = Booking::where([
            'building_id' => $this->building_id,
            'floor_number' => $this->floor_number,
            'status' => self::STATUS_COMPLETED
        ])->where(function ($query) {
            $query->where(function ($query) {
                $query->where('check_in_date', '<=', $this->check_in_date)->where('check_out_date', '>=', $this->check_in_date);
            })->orWhere(function ($query) {
                $query->where('check_in_date', '<=', $this->check_out_date)->orWhere('check_out_date', '>=', $this->check_out_date);
            });
        })->get();

        $total = 0;
        $male_single_bookings = 0;
        $male_room_bookings = 0;
        $female_single_bookings = 0;
        $female_room_bookings = 0;
        foreach ($bookings as $booking) {
            $total++;
            if ($booking->user->userProfile->gender_id == UserProfile::GENDER_MALE) {
                if ($booking->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                    $male_single_bookings++;
                } else {
                    $male_room_bookings++;
                }
            } else {
                if ($booking->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                    $female_single_bookings++;
                } else {
                    $female_room_bookings++;
                }
            }
        }
        \Log::info('$total' . $total . '$male_single_bookings' . $male_single_bookings . '$male_room_bookings' . $male_room_bookings . '$female_single_bookings' . $female_single_bookings . '$female_room_bookings' . $female_room_bookings);
        $male_rooms = 0;
        $male_single_bed = false;
        if ($male_single_bookings % 2 == 0) {
            $male_rooms = $male_room_bookings + $male_single_bookings / 2;
        } else {
            $male_single_bookings = $male_single_bookings - 1;
            $male_rooms = $male_room_bookings + $male_single_bookings / 2 + 1;
            $male_single_bed = true;
        }
        \Log::info('$male_rooms' . $male_rooms . '$male_single_bed' . $male_single_bed);

        $female_rooms = 0;
        $female_single_bed = false;
        if ($female_single_bookings % 2 == 0) {
            $female_rooms = $female_room_bookings + $female_single_bookings / 2;
        } else {
            $female_single_bookings = $female_single_bookings - 1;
            $female_rooms = $female_room_bookings + $female_single_bookings / 2 + 1;
            $female_single_bed = true;
        }
        \Log::info('$female_rooms' . $female_rooms . '$female_single_bed' . $female_single_bed);
        $total = $male_rooms + $female_rooms;

        $rooms = Room::where([
            'building_id' => $this->building_id,
            'floor_number' => $this->floor_number,
        ])->where(function ($query) {
            $query->whereNull('is_blocked')->orWhere('is_blocked', Room::IS_AVAILABLE);
        })->get();
        \Log::info('$rooms' . $rooms);
        $na_rooms = 0;
        $m_rooms = 0;
        $f_rooms = 0;
        foreach ($rooms as $room) {
            if ($room->gender == Room::GENDER_NA) {
                $na_rooms++;
            } elseif ($room->gender == Room::GENDER_MALE) {
                $m_rooms++;
            } else {
                $f_rooms++;
            }
        }
        \Log::info('$total' . $total . '$na_rooms' . $na_rooms . '$m_rooms' . $m_rooms . '$f_rooms' . $f_rooms);
        $gender = $this->getGender();

        if ($gender == UserProfile::GENDER_FEMALE) {
            $total_rooms = $na_rooms + $f_rooms;
            if ($this->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                \Log::info('$fgtotal' . $total . '$ftotal_rooms' . $total_rooms);
                if ($female_single_bed == true) {
                    $gtotal = $total - 1;
                    if ($gtotal < $total_rooms) {
                        return true;
                    }
                } else {
                    if ($total < $total_rooms) {
                        return true;
                    }
                }

            } else {
                if ($total < $total_rooms) {
                    return true;
                }
            }
        } else {
            $total_rooms = $na_rooms + $m_rooms;
            \Log::info('$fgtotal' . $total . '$mtotal_rooms' . $total_rooms);
            if ($this->booking_type == Booking::BOOKING_TYPE_SINGLE_BED) {
                if ($male_single_bed == true) {
                    if ($total - 1 < $total_rooms) {
                        return true;
                    }
                } else {
                    if ($total < $total_rooms) {
                        return true;
                    }
                }
            } else {
                if ($total < $total_rooms) {
                    return true;
                }
            }
        }


        return false;
    }

    public function getGender()
    {
        return $this->user->userProfile->gender;
    }

    public function saveTransaction($amount)
    {
        $transaction = Transaction::where('booking_id', $this->id)->first();

        if ($transaction == null) {
            $transaction = Transaction::create([
                'user_id' => $this->user_id,
                'booking_id' => $this->id,
                'txn_id' => 'Temp-' . str_random(5),
                'amount' => $amount,
                'status' => Transaction::STATUS_PENDING
            ]);
        }

        $transaction->saveItems();
        return $transaction->id;
    }

    public function getServicePrices()
    {
        $services = $this->services;
        $price = 0;
        if ($services->count() > 0) {
            foreach ($services as $service) {
                $price = $price + $service->service->price;
            }
        }
        return $price;
    }

    public function saveMembers($request)
    {
        $name_ar = isset($request->get('member')['name']) ? $request->get('member')['name'] : [];
        $age_ar = isset($request->get('member')['age']) ? $request->get('member')['age'] : [];
        $gender_ar = isset($request->get('member')['gender']) ? $request->get('member')['gender'] : [];
        $id_proof_ar = !empty($request->file('member')) ? $request->file('member')['id_proof'] : [];
        $ids = isset($request->get('member')['id']) ? $request->get('member')['id'] : [];
        $building_ids = !empty($request->get('member')['member_building_id']) ? $request->get('member')['member_building_id'] : [];
        $floor_numbers = !empty($request->get('member')['member_floor_number']) ? $request->get('member')['member_floor_number'] : [];
        $booking_types = !empty($request->get('member')['member_booking_type']) ? $request->get('member')['member_booking_type'] : [];
        $is_child = !empty($request->get('member')['member_is_child']) ? $request->get('member')['member_is_child'] : [];
        $is_driver = !empty($request->get('member')['member_is_driver']) ? $request->get('member')['member_is_driver'] : [];
        $child_count = !empty($request->get('member')['member_child_count']) ? $request->get('member')['member_child_count'] : [];
        $driver_count = !empty($request->get('member')['member_driver_count']) ? $request->get('member')['member_driver_count'] : [];
        $check_in = !empty($request->get('member')['member_check_in_date']) ? $request->get('member')['member_check_in_date'] : [];
        $check_out = !empty($request->get('member')['member_check_out_date']) ? $request->get('member')['member_check_out_date'] : [];


        $members = [];
        $key = 0;
        foreach ($name_ar as $name) {
            $members[$key]['name'] = $name;
            $key++;
        }
        $key = 0;
        foreach ($age_ar as $age) {
            $members[$key]['age'] = $age;
            $key++;
        }
        $key = 0;
        foreach ($gender_ar as $gender) {
            $members[$key]['gender'] = $gender;
            $key++;
        }
        $key = 0;
        foreach ($id_proof_ar as $id_proof) {
            $members[$key]['id_proof'] = Settings::saveUploadedFile($id_proof);
            $key++;
        }
        $key = 0;
        foreach ($ids as $id) {
            $members[$key]['id'] = $id;
            $key++;
        }

        $key = 0;
        foreach ($building_ids as $building_id) {
            $members[$key]['building_id'] = $building_id;
            $key++;
        }

        $key = 0;
        foreach ($floor_numbers as $floor_number) {
            $members[$key]['floor_number'] = $floor_number;
            $key++;
        }

        $key = 0;
        foreach ($booking_types as $booking_type) {
            $members[$key]['booking_type'] = $booking_type;
            $key++;
        }

        $key = 0;
        foreach ($is_child as $childe) {
            $members[$key]['is_child'] = 1;
            $key++;
        }

        $key = 0;
        foreach ($is_driver as $driver) {
            $members[$key]['is_driver'] = 1;
            $key++;
        }

        $key = 0;
        foreach ($child_count as $childcount) {
            $members[$key]['child_count'] = $childcount;
            $key++;
        }

        $key = 0;
        foreach ($driver_count as $drivercount) {
            $members[$key]['driver_count'] = $drivercount;
            $key++;
        }


        $key = 0;
        foreach ($check_in as $checkin) {
            $members[$key]['check_in_date'] = date('Y-m-d', strtotime($checkin));
            $key++;
        }


        $key = 0;
        foreach ($check_out as $checkout) {
            $members[$key]['check_out_date'] = date('Y-m-d', strtotime($checkout));
            $key++;
        }


        foreach ($members as $member) {
            $member_model = new Member();
            if ($member['id'] != "") {
                $member_model = Member::find($member['id']);
            }
            $member['is_child'] = isset($member['is_child']) ? $member['is_child'] : "";
            $member['child_count'] = isset($member['child_count']) ? $member['child_count'] : 0;
            $member['is_driver'] = isset($member['is_driver']) ? $member['is_driver'] : "";
            $member['driver_count'] = isset($member['driver_count']) ? $member['driver_count'] : 0;

            if ($member['is_child'] != "") {
                if ($member['child_count'] > 0) {
                    $child_count = $member['child_count'];
                } else {
                    $child_count = 1;
                }
            } else {
                $child_count = 0;
            }
            if ($member['is_driver'] != "") {
                if ($member['driver_count'] > 0) {
                    $driver_count = $member['driver_count'];
                } else {
                    $driver_count = 1;
                }
            } else {
                $driver_count = 0;
            }


            if (!empty($member['name'])) {
                $member_model->name = isset($member['name']) ? $member['name'] : "";
                $member_model->age = isset($member['age']) ? $member['age'] : "";
                $member_model->gender = isset($member['gender']) ? $member['gender'] : "";
                $member_model->id_proof = isset($member['id_proof']) ? $member['id_proof'] : "";
                $member_model->is_child = isset($member['is_child']) ? $member['is_child'] : "";
                $member_model->is_driver = isset($member['is_driver']) ? $member['is_driver'] : "";
                $member_model->child_count = $child_count;
                $member_model->driver_count = $driver_count;
                $member_model->building_id = isset($member['building_id']) ? $member['building_id'] : "";
                $member_model->floor_number = isset($member['floor_number']) ? $member['floor_number'] : "";
                $member_model->booking_type = isset($member['booking_type']) ? $member['booking_type'] : "";
                $member_model->check_in_date = isset($member['check_in_date']) ? $member['check_in_date'] : "";
                $member_model->check_out_date = isset($member['check_out_date']) ? $member['check_out_date'] : "";


                $member_model->booking_id = $this->id;
                $member_model->user_id = $this->user_id;
                $member_model->status = Booking::STATUS_PENDING;
                $member_model->is_attendant = Member::IS_ATTENDANT;
                $member_model->save();
            }
        }
    }

    public function getRoomDetails()
    {
        if ($this->room !== null) {
            $html = $this->room->building->name . '-' . $this->room->getFloorNumber($this->room->floor_number) . '-' . $this->room->room_number;

            return $html;
        }
        return "";
    }

    public function getServiceDetails()
    {
        $html = "";
        $services = $this->getServices();
        if (!empty($services)) {
            foreach ($services as $service) {
                $html .= "Name: " . $service['service_name'] . "(Rs." . $service['service_price'] . "/day)" . PHP_EOL;
            }
        }
        return $html;
    }

    public function getServices()
    {
        $services = $this->getAllServices();
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

    public function getAllServices()
    {
        $services = UserExtraService::where('booking_id', $this->id)->get();
        return $services;
    }

    public function bookingValidity()
    {
        return true;
        if ($this->status == self::STATUS_COMPLETED) {
            $date = date("Y-m-d");
            $booking_date_from = date("Y-m-d", strtotime($this->created_at));
            $booking_date_to = date("Y-m-d", strtotime($this->created_at));
            if ($this->patient_type == self::PATIENT_TYPE_IPD) {
                $booking_date_from = date("Y-m-d", strtotime($this->check_in_date));
                $booking_date_to = date("Y-m-d", strtotime($this->check_out_date));
            } else {
                return true;
            }
            if ($date >= $booking_date_from /*&& $date <= $booking_date_to*/) {
                return true;
            }
        }
        return false;
    }

    public function daysPrice($room_id = null, $ser = true)
    {
        $price = $this->getPrice($room_id);
        $now = strtotime($this->check_in_date); // or your date as well
        $your_date = strtotime($this->check_out_date);

        if ($your_date > date("Y-m-d")) {
            $your_date = strtotime("today");
        }

        $datediff = $your_date - $now;

        $days = floor($datediff / (60 * 60 * 24));

        $booking_price = $days * $price;
        $total = $booking_price;
        if ($ser == true) {
            $service_price = 0;
            $services = $this->getAllServices();
            if (!empty($services)) {
                foreach ($services as $service) {
                    $service_price += $service->daysPrice();
                }
            }

            $total = $booking_price + $service_price;
        }
        return $total;
    }

    public function getPrice($room_id = null)
    {
        $room = Room::find($room_id);
        if ($room_id == null) {
            $room = $this->room;
        }
        $price = 0;
        if ($room != null) {
            $price = $room->room_price;
            $type = $this->booking_type;

            if ($type == self::BOOKING_TYPE_SINGLE_BED) {
                $price = $room->bed_price;
            }
        }

        return $price;
    }

    public function labTests()
    {
        return $this->hasMany('App\PatientLabTest', 'booking_id');
    }

	public function misc()
	{
	
		return $this->hasOne('App\Misc', 'booking_id');
	}

    public function getPendingAmount($discharge = false)
    {
        $payable = $this->getPayableAmount($discharge);
        $paid = $this->getPaidAmount();
        $refund = $this->getPaidAmount(true);
        $payable = $payable + $refund;
        if ($paid < $payable) {
            return $payable - $paid;
        }

        return 0;
    }

    public function getPendingAmountWithoutBill($discharge = false)
    {
        $payable = $this->getPayableAmountWithoutBill();
        $paid = $this->getPaidAmountWithoutBill();
        $refund = $this->getPaidAmountWithoutBill(true);
        $payable = $payable + $refund;

        //Get previous bills remaining amount
        $remaining_bill = $this->lastBill();
        if($remaining_bill) {
            $payable = $payable + $remaining_bill->remaining_amount;
        }

        if ($paid < $payable) {
            return $payable - $paid;
        }

        return 0;
    }
    public function getPayableAmount($discharge = false)
    {
        return $this->getTotalAmount($discharge) - $this->getDiscountsAmount();
    }

    public function getPayableAmountWithoutBill() {
        return $this->getTotalAmountWithoutBill(true) - $this->getDiscountsAmountWithoutBill();
    }

    public function getTotalAmount($discharge = false)
    {
        $accomodation = $this->getAccomodationAmount($discharge, true);
        //$service = $this->getServicesAmount($discharge);
        $diet = $this->getDietAmount();
        $treatments = $this->getTreatmentsAmount();
        $lab = $this->getLabAmount();
        //$consult = ConsultationCharge::getConsultFees();
        $consult = $this->getConsultationAmount($discharge, true);
 	$misc = $this->getMiscAmount();
        $total = $accomodation + $diet + $treatments + $lab + $consult + $misc;

        return $total;
    }
    public function getTotalAmountWithoutBill($discharge = false)
    {
        $accomodation = $this->getAccomodationAmount($discharge, true);
       
        //$service = $this->getServicesAmount($discharge);
        $diet = $this->getDietAmountWithoutBill();
        $treatments = $this->getTreatmentsAmountWithoutBill();
        $lab = $this->getLabAmountWithoutBill();
        //$consult = ConsultationCharge::getConsultFees();
        $consult = $this->getConsultationAmountWithoutBill($discharge, true);
 	    $misc = $this->getMiscAmountWithoutBill();
        $total = $accomodation + $diet + $treatments + $lab + $consult + $misc;
     
        return $total;
    }

    public function getAccomodationAmount($discharge = false, $member = false)
    {
        $rooms = $this->bookingRooms;
        if ($member == true) {
            $rooms = $this->bookingRoomsAll;
        }

        $price = 0;
        $service_price = 0;
        if ($rooms->count() > 0) {
            foreach ($rooms as $room) {
                if ($discharge == true) {
                    //$price = $price + $room->allDaysPrice($room->room_id, false);

                    //With services
                    $price = $price + $room->allDaysPrice($room->room_id, true, true);

                    $service_price = $service_price + $room->allDaysPrice($room->room_id, true);
                } else {
                    $price = $price + $room->allDaysPrice($room->room_id, true, false);
                    $service_price = $service_price + $room->daysPrice($room->room_id, true);
                }
            }
        }

        return $price;
    }

    public function getServicesAmount($discharge = false)
    {
        $rooms = $this->bookingRooms;
        $service_price = 0;
        if ($rooms->count() > 0) {
            foreach ($rooms as $room) {
                foreach ($room->userServices as $service) {
                    $service_price = $discharge == true ? $service->daysPrice(true) : $service->daysPrice();
                    //$service_price = $service_price + $service->daysPrice();
                }
            }
        }

        return $service_price;
    }

    public function getDietAmount()
    {
        $diets = $this->getDiets();
        $price = 0;
        foreach ($diets as $diet) {
            $diet_daily_items = DietDailyStatus::where('diet_id', $diet->id)->get();

            foreach ($diet_daily_items as $item) {
                $price = $price + $item->getTotalAmount();
            }
        }
        return $price;
    }

    public function getDietAmountWithoutBill()
    {
        $diets = $this->getDietsWithoutBills();
        $price = 0;
        foreach ($diets as $diet) {
            $diet_daily_items = DietDailyStatus::where('diet_id', $diet->id)->get();

            foreach ($diet_daily_items as $item) {
                $price = $price + $item->getTotalAmount();
            }
        }
        return $price;
    }

    public function getDietsWithoutBills()
    {
        $diets = DietChart::doesntHave('bill')->where('booking_id', $this->id)->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        return $diets;
    }

    public function diets()
    {

        return $this->hasMany(DietChart::class, 'booking_id');
        //$diets = DietChart::where('booking_id', $this->id)->get();
        // return $diets;
    }

    public function getDiets()
    {
        $diets = DietChart::where('booking_id', $this->id)->get();
        return $diets;
    }

    public function getTreatmentsAmount($department_id = null)
    {
        $treatment_tokens = $this->getTreatments($department_id);
        $price = 0;
        if ($treatment_tokens->count() > 0) {
            foreach ($treatment_tokens as $treatment_token) {
                $treatments = $treatment_token->treatments;
                if ($treatments->count() > 0) {
                    foreach ($treatments as $treatment) {
                        if ($treatment->status == PatientTreatment::STATUS_COMPLETED) {
                            $price = $price + $treatment->price;
                        }
                    }
                }
            }
        }
        return $price;
    }

    public function getTreatments($department_id = null)
    {
        $treatments = TreatmentToken::where('booking_id', $this->id);

        if ($department_id != null) {
            $treatments = $treatments->where('department_id', $department_id);
        }
        $treatments = $treatments->get();
        return $treatments;
    }

    public function getTreatmentsAmountWithoutBill($department_id = null)
    {
        $treatment_tokens = $this->getTreatmentsWithoutBill($department_id);
        $price = 0;
        if ($treatment_tokens->count() > 0) {
            foreach ($treatment_tokens as $treatment_token) {
                $treatments = $treatment_token->treatments;
                if ($treatments->count() > 0) {
                    foreach ($treatments as $treatment) {
                        if ($treatment->status == PatientTreatment::STATUS_COMPLETED) {
                            $price = $price + $treatment->price;
                        }
                    }
                }
            }
        }
        return $price;
    }

    public function getTreatmentsWithoutBill($department_id = null)
    {
        $treatments = TreatmentToken::doesntHave('bill')->where('booking_id', $this->id)->where('created_at', '<=', date('Y-m-d H:i:s'));

        if ($department_id != null) {
            $treatments = $treatments->where('department_id', $department_id);
        }
        $treatments = $treatments->get();
        return $treatments;
    }

    public function paidItems()
    {
        return $this->hasMany(Wallet::class, 'booking_id')->where('status', Wallet::STATUS_PAID)->where('type', Wallet::TYPE_PAID);
    }

    public function paidItemsWithoutBill()
    {
        return $this->hasMany(Wallet::class, 'booking_id')->where('status', Wallet::STATUS_PAID)->where('type', Wallet::TYPE_PAID)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'));
    }

    public function pendingItems()
    {
        return $this->hasMany(Wallet::class, 'booking_id')->where('status', Wallet::STATUS_PENDING)->where('type', Wallet::STATUS_PENDING);
    }

    public function getLabAmount($discharge = false)
    {
        $lab_tests = $this->labTests;
        $am = 0;

        foreach ($lab_tests as $lab_test) {
            $am = $am + $lab_test->getPrice($discharge);
        }

        return $am;
    }

    public function getLabAmountWithoutBill($discharge = false)
    {
        $lab_tests = PatientLabTest::where('booking_id', $this->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        $am = 0;

        foreach ($lab_tests as $lab_test) {
            $am = $am + $lab_test->getPrice($discharge);
        }

        return $am;
    }

    public function labTestsWithoutBill() {
        $lab_tests = PatientLabTest::where('booking_id', $this->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
      
        return $lab_tests;
    }

	public function getMiscAmount() 
	{
        $misc = Misc::where('booking_id', $this->id)->first();

		if ($misc) {
			return $misc->price;
		}
		return 0;

	}

    public function getMiscAmountWithoutBill() 
	{
        $misc = Misc::where('booking_id', $this->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->first();

		if ($misc) {
			return $misc->price;
		}
		return 0;
	}

    public function getDiscountsAmount()
    {
        $price = 0;
        if ($this->discounts->count() > 0) {
            foreach ($this->discounts as $discount) {
                $price = $price + $discount->discount_amount;
            }
        }
        return $price;
    }

    public function getDiscountsAmountWithoutBill()
    {
        $price = 0;
        $discounts = BookingDiscount::where('booking_id', $this->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        if ($discounts->count() > 0) {
            foreach ($discounts as $discount) {
                $price = $price + $discount->discount_amount;
            }
        }
        return $price;
    }

    public function getDiscountsWithoutBill()
    {
        $discounts = BookingDiscount::where('booking_id', $this->id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        return $discounts;
    }

    public function getPendingStatusAmount()
    {
        $payments = Wallet::where([
            'booking_id' => $this->id,
            'status' => Wallet::STATUS_PENDING,
        ])->first();

        if ($payments) {
            return $payments->amount;
        }

        return 0;
    }

    public function getPaidAmountWithoutBill($refund = false)
    {
        $payments = Wallet::doesntHave('bill')->where([
            'booking_id' => $this->id,
            'status' => Wallet::STATUS_PAID
        ])->where('created_at', '<=', date('Y-m-d H:i:s'))->get();

        $price = 0;
        if ($payments->count() > 0) {
            foreach ($payments as $payment) {
                if ($refund == false) {
                    if ($payment->type == Wallet::TYPE_PAID) {
                        $price = $price + $payment->amount;
                    }
                } elseif ($payment->type == Wallet::TYPE_REFUND) {
                    $price = $price + (int)$payment->amount;
                }
            }
        }
        
        $consul_amount = $this->getConsultationAmountWithoutBill();
        if ($consul_amount > 0) {
            $price - $consul_amount;
        }
        return $price;
    }

    public function getPaidAmount($refund = false)
    {
        $payments = Wallet::where([
            'booking_id' => $this->id,
            'status' => Wallet::STATUS_PAID
        ])->get();

        $price = 0;
        if ($payments->count() > 0) {
            foreach ($payments as $payment) {
                if ($refund == false) {
                    if ($payment->type == Wallet::TYPE_PAID) {
                        $price = $price + $payment->amount;
                    }
                } elseif ($payment->type == Wallet::TYPE_REFUND) {
                    $price = $price + (int)$payment->amount;
                }
            }
        }

        return $price;
    }

    public function getRefundAmount($discharge = false)
    {
        $payable = $this->getPayableAmount($discharge);
        $paid = $this->getPaidAmount();
        $refund = $this->getPaidAmount(true);
        $payable = $payable + $refund;

        if ($paid > $payable) {
            return ($paid - $payable);
        }

        return 0;
    }

    public function getRefundAmountWithoutBill()
    {
        $payable = $this->getPayableAmountWithoutBill();
        $paid = $this->getPaidAmountWithoutBill();
        $refund = $this->getPaidAmountWithoutBill(true);
        $payable = $payable + $refund;

        $bill = $this->lastBill();
        if($bill){
            $payable = $payable + $bill->remaining_amount;
        }
        if ($paid > $payable) {
            return ($paid - $payable);
        }

        return 0;
    }

    public function getCurrentBooking($type = null, $current = true)
    {
        $booking = BookingRoom::where([
            'booking_id' => $this->id,
        ])->whereNull('member_id')->orderBy('created_at', 'DESC')->first();

        if ($booking != null && isset($booking->room->building->name)) {
            if ($type == "building_name") {
                return $booking->room->building->name;
            } elseif ($type == "booking_type") {
                return $booking->type;
            } elseif ($type == "floor_number") {
                return $booking->room->floor_number;
            } elseif ($type == 'dates') {
                return date('d-m-Y', strtotime($booking->check_in_date)) . " to " . date('d-m-Y', strtotime($booking->check_out_date));
            } elseif ($type == 'total_price') {
                return $booking->allDaysPrice(null, true, $current);
            } elseif ($type == "room_no") {
                $price = $booking->room->room_price;
                if ($booking->type == BookingRoom::BOOKING_TYPE_SINGLE_BED)
                    $price = $booking->room->bed_price;
                return $booking->room->room_number . '-' . $price . '/day';
            } elseif ($type == "services") {
                $html = "";
                $services = $booking->userServices;
                if (!empty($services)) {
                    foreach ($services as $service) {
                        $html .= "Name: " . $service->service_name . "(Rs." . $service->price . "/day)" . PHP_EOL;
                    }
                }
                return $html;
            }
            return true;
        }

        return false;
    }

    public function discharge($status = self::STATUS_DISCHARGED)
    {
        //return "dgfghshgsdhgsdhgf";
        //Update user profile
        $this->user->userProfile->update([
            'booking_id' => $this->id
        ]);

        //Discharge Rooms and services
        $rooms = $this->bookingRoomsAll;
        //dd($rooms);
        if ($rooms->count() > 0) {
            foreach ($rooms as $bookingRoom) {
                if ($bookingRoom->check_in_date < date("Y-m-d")) {
                    if($bookingRoom->check_out_date >= date("Y-m-d")){
                        $bookingRoom->update([
                            'check_out_date' => date("Y-m-d"),
                            'status' => $status
                        ]);
                        $services = $bookingRoom->userServices;
                        if ($services->count() > 0) {
                            foreach ($services as $service) {
                                $service_end_date = $service->service_end_date;
                                $service_start_date = $service->service_start_date;
                                if ($service_start_date != null && $service_end_date != null) {
                                    $service->update([
                                        'service_end_date' => date("Y-m-d"),
                                        'status' => $status
                                    ]);
                                }

                            }
                        }
                    }
                } else {
                    $services = $bookingRoom->userServices;
                    if ($services->count() > 0) {
                        foreach ($services as $service) {
                            $service->delete();
                        }
                    }
                    $bookingRoom->delete();
                }
            }
        }

        //Update Diet Charts
        $diet_charts = DietChart::discharge($this->user_id, $this->id, $status);

        //update treatment tokens
        $tokens = TreatmentToken::discharge($this->user_id, $this->id, $status);

        //Update Lab Tests
        $tests = PatientLabTest::discharge($this->user_id, $this->id, $status);

        //Update Patient Details
        $details = PatientDetails::discharge($this->user_id, $this->id, $status);

        //Update vital data
        $data = VitalData::discharge($this->user_id, $this->id, $status);


        //update diagnossis
        $diag = PatientDiagnosis::discharge($this->user_id, $this->id, $status);

        //Update Examinations Report
        $ashtvidhs = AyurvedaAshtvidhExamination::discharge($this->user_id, $this->id, $status);
        $aturs = AyurvedAturExamination::discharge($this->user_id, $this->id, $status);
        $doshes = AyurvedDoshExamination::discharge($this->user_id, $this->id, $status);
        $cardios = CardiovascularExamination::discharge($this->user_id, $this->id, $status);
        $gastros = GastrointestinalExamination::discharge($this->user_id, $this->id, $status);
        $genitos = GenitourinaryExamination::discharge($this->user_id, $this->id, $status);
        $neuros = NeurologicalExamination::discharge($this->user_id, $this->id, $status);
        $physicals = PhysicalExamination::discharge($this->user_id, $this->id, $status);
        $respiratories = RespiratoryExamination::discharge($this->user_id, $this->id, $status);

        //update Wallet
        // $wallets = Wallet::discharge($this->user_id);

        //Discharge Patients
        $discharge_patients = DischargePatient::discharge($this->user_id, $this->id, $status);

        $this->update([
            'status' => $status
        ]);
    }

    public function isEditable($discharge = true)
    {
        /*if ($this->patient_type == self::PATIENT_TYPE_IPD) {
            if ($this->accommodation_status != self::ACCOMMODATION_STATUS_CONFIRMED) {
                return false;
            } else {
                return true;
            }
        }*/

        if ($discharge == true) {
            if ($this->status != self::STATUS_DISCHARGED)
                return true;
        }


        return false;
    }

    public function treatments()
    {
        return $this->hasMany('App\TreatmentToken', 'booking_id');

    }

    public function getAddress($attr = null)
    {
        $address = isset($this->userProfile) ? $this->userProfile->address : null;
        if (isset($address)) {
            if ($attr != null) {
                return $address->$attr;
            }
            return true;
        }
        return false;
    }

    public function getDate()
    {
        if ($this->patient_type == self::PATIENT_TYPE_OPD)
            return $this->created_at;
        if ($this->bookedRoom() != null)
            return $this->bookedRoom()->check_in_date;
        return $this->check_in_date;
    }

    public function bookedRoom()
    {
        $room = BookingRoom::where('booking_id', $this->id)->whereNull('member_id')->first();
        return $room;
    }

    public function getComplaints()
    {
        $healthIssues = isset($this->healthIssues->health_issues) ? $this->healthIssues->health_issues != null ? $this->healthIssues->health_issues : $this->user->userProfile->health_issues : "";
        return "Present Complaints: " . $healthIssues;
    }

    public function checkPaymentTab()
    {
        if ($this->id != null) {
            if ($this->userProfile != null) {
                if ($this->userProfile->id != null) {
                    if ($this->userProfile->health_issues != null) {
                        if ($this->checkAccommodation()) {
                            if ($this->building != null) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function checkAccommodation()
    {
        if ($this->patient_type == \App\UserProfile::PATIENT_TYPE_OPD) {
            return false;
        }

        return true;
    }

    public function setMailData()
    {
        $data['subject'] = 'Booking is Confirmed';
        $data['name'] = $this->user->name;
        $data['email'] = $this->user->email;
        $data['booking_id'] = $this->booking_id;
        $data['patient_id'] = $this->userProfile->kid;
        $data['registration_id'] = $this->user->registration_id;
        $data['booking_dates'] = $this->building_id != "" ? $this->check_in_date . " - " . $this->check_out_date : date('Y-m-d', strtotime($this->created_at));
        $data['checkin'] = isset($this->check_in_date) ? $this->check_in_date : 'NA - Not Available';
        $data['checkout'] = isset($this->check_out_date) ? $this->check_out_date : 'NA - Not Available';
        $data['type'] = $this->getBookingType($this->booking_type);
        $data['floor'] = !empty(\App\Room::getFloorNumber($this->floor_number)) ? \App\Room::getFloorNumber($this->floor_number) : 'NA - Not Available';
        $data['building'] = isset($this->building->name) ? $this->building->name : 'NA - Not Available';
        $members = $this->members;
        $data['member_name'] = 'NA - Not Available';
        $data['age'] = 'NA - Not Available';
        $data['provider_id'] = 'NA - Not Available';
        $data['gender'] = 'NA - Not Available';
        if (!empty($members)) {
            foreach ($members as $member) {
                $data['member_name'] = $member->name;
                $data['age'] = $member->age;
                $data['gender'] = $member->getGenderOptions($member->gender);
                break;
            }
        }


        return $data;
    }

    public function isAllowed()
    {
        if (!$this->isCancelled()) {
            if (\Auth::user()->isPatient()) {
                if ($this->user_id == \Auth::user()->id)
                    return true;

            } else {
                return true;
            }
        }
        return false;
    }

    public function customDelete()
    {
        //Update user profile
        if ($this->userProfile != null) {
            $this->userProfile->customDelete();
        }
        if ($this->address != null) {
            $this->address->customDelete();
        }
        //Discharge Rooms and services
        $rooms = $this->bookingRooms;
        if ($rooms->count() > 0) {
            foreach ($rooms as $bookingRoom) {
                $bookingRoom->delete();
                $services = $bookingRoom->userServices;
                if ($services->count() > 0) {
                    foreach ($services as $service) {
                        $service->delete();
                    }
                }
            }
        }

        //Update Diet Charts
        $diet_charts = DietChart::customDelete($this->user_id, $this->id);

        //update treatment tokens
        $tokens = TreatmentToken::customDelete($this->user_id, $this->id);

        //Update Lab Tests
        $tests = PatientLabTest::customDelete($this->user_id, $this->id);

        //Update Patient Details
        $details = PatientDetails::customDelete($this->user_id, $this->id);

        //Update vital data
        $data = VitalData::customDelete($this->user_id, $this->id);


        //update diagnossis
        $diag = PatientDiagnosis::customDelete($this->user_id, $this->id);

        //Update Examinations Report
        $ashtvidhs = AyurvedaAshtvidhExamination::customDelete($this->user_id, $this->id);
        $aturs = AyurvedAturExamination::customDelete($this->user_id, $this->id);
        $doshes = AyurvedDoshExamination::customDelete($this->user_id, $this->id);
        $cardios = CardiovascularExamination::customDelete($this->user_id, $this->id);
        $gastros = GastrointestinalExamination::customDelete($this->user_id, $this->id);
        $genitos = GenitourinaryExamination::customDelete($this->user_id, $this->id);
        $neuros = NeurologicalExamination::customDelete($this->user_id, $this->id);
        $physicals = PhysicalExamination::customDelete($this->user_id, $this->id);
        $respiratories = RespiratoryExamination::customDelete($this->user_id, $this->id);

        //update Wallet
        $wallets = Wallet::customDelete($this->id);

        //Discharge Patients
        $discharge_patients = DischargePatient::customDelete($this->user_id, $this->id);

        $this->delete();
    }

    public function getConsultFees()
    {
        $paid = $this->getPaidAmount();
        $fees = ConsultationCharge::getConsultFees();
        if ($fees > $paid) {
            return $fees - $paid;
        }
        return 0;
    }

    public function isDeleteAble()
    {
        if ($this->bookingRooms->count() > 0 || $this->treatments->count() > 0 || $this->diets->count() > 0) {
            return false;
        }

        return true;
    }

    public function getAccommodationStatus()
    {
        if ($this->checkAccommodation()) {

            if ($this->bookedRoom() != null) {
                if ($this->members->count() > 0) {
                    $ok = true;
                    foreach ($this->members as $member) {
                        if ($member->bookedRoom() == null) {
                            $ok = false;
                            break;
                        }
                    }
                    if ($ok == false) {
                        return "Member Pending";
                    }
                }
                return "Attended";
            }
            return "Pending";
        }
        return "NA";
    }

    public function getStatusClass()
    {
        if ($this->status == self::STATUS_CANCELLED) {
            return 'danger';
        }

        if ($this->status == self::STATUS_PENDING) {
            return 'pending';
        }

        if ($this->status == self::STATUS_COMPLETED) {
            return 'completed';
        }
        return '';
    }

    public function getDietStatus($type)
    {
        $diet = DietChart::where('booking_id', $this->id)->where('start_date', (string)date("Y-m-d"))->first();
        if ($diet != null) {
            $daily_diet = DietDailyStatus::where('diet_id', $diet->id)->first();
            $timestamp = DietChart::getCurrentTime();
            $chart_times = DietChart::getTimes($type);


            $chart_times['start'] = strtotime(date("H:i:s", strtotime($chart_times['start'])));
            $chart_times['end'] = strtotime(date("H:i:s", strtotime($chart_times['end'])));

            if ($daily_diet != null) {
                $attr = DietChart::getChartAttribute($type);

                if ($timestamp > $chart_times['end'])
                    return $daily_diet->$attr != 0 ? "Done" : "Didn't Came";

                return $daily_diet->$attr != 0 ? "Done" : "Pending";
            }

            if ($timestamp > $chart_times['end'])
                return "Didn't Came";
        }
        return "Pending";
    }

    public function getFormatedDate($attr)
    {
        if ($this->$attr == '0000-00-00 00:00:00') {
            return "";
        }
        if ($this->$attr == null) {
            return "";
        }
        return date("Y-m-d", strtotime($this->$attr));
    }

    public function getUrl($link)
    {
        if (\Auth::user()->isUser()) {
            return url('user/booking/' . $this->id . '/' . $link);
        }
        return url('admin/booking/' . $this->id . '/' . $link);
    }

    public static function getBookingId($id)
    {
        $booking = Booking::where('id', '!=', $id)->where('accommodation_status', self::ACCOMMODATION_STATUS_CONFIRMED)->where('status', self::STATUS_COMPLETED)->orderBy('id', 'desc')->first();

        if ($booking) {
            return ((int)str_replace('R', '', $booking->booking_id)) + 1;
        }

        return 1;


    }

    public function getCurrentAccomodationDetails()
    {
        $booking_room = BookingRoom::where('booking_id', $this->id)->where('check_in_date', '<=', (string)date('Y-m-d H:i:s'))->where('check_out_date', '>=', (string)date('Y-m-d H:i:s'))->first();

        if ($booking_room == null) {
            $booking_room = BookingRoom::where('booking_id', $this->id)->first();
        }

        $ac_details = '';

        if ($booking_room) {
            $ac_details = $booking_room->roomDetails();
            $checkin = date("d-m-Y", strtotime($booking_room->check_in_date));
            $checkout = date("d-m-Y", strtotime($booking_room->check_out_date));
            $ac_details .= ' From ' . $checkin . ' To ' . $checkout;
        }

        return $ac_details;
    }

    public function getConsultationAmount()
    {
        $tokens = OpdTokens::where('booking_id', $this->id)->get();
        $price = 0;
        foreach ($tokens as $token) {
            $price += $token->charges;
        }

        return $price;
    }

    public function getConsultationAmountWithoutBill()
    {
        $tokens = OpdTokens::with('bill')->doesntHave('bill')->where('booking_id', $this->id)
         ->where('created_at', '<=', date('Y-m-d H:i:s'))
         ->get();
         
        $price = 0;
        foreach ($tokens as $token) {
            $price += $token->charges;
        }

        return $price;
    }

    public function getIdNumber()
    {
        $booking = Booking::orderBy('booking_id', 'DESC')->where('id', '!=', $this->id)->whereNotNull('booking_id')->first();

        if ($booking) {
            $b_id = str_replace('B', '', $booking->booking_id);

            $b_id = (int)$b_id + 1;
            return 'B' . sprintf("%04s", $b_id);
        }

        return 'B' . sprintf("%04s", 1);;
    }


    public function getKIdNumber()
    {
        $booking = Booking::where('patient_type', $this->patient_type)->orderBy('id', 'DESC')->where('id', '!=', $this->id)->whereNotNull('booking_kid')->first();

        if ($booking) {
            $kid = str_replace('K-OPD', '', $booking->booking_kid);

            if ($this->patient_type == Booking::PATIENT_TYPE_IPD) {
                $kid = str_replace('K-IPD', '', $booking->booking_kid);
            }

            $kid = (int)$kid + 1;
            $limit =  $kid;
            for($i = $kid; $i = $limit ; $i++){
                $kid_check = User::getId("K-OPD", $i);
                if ($this->patient_type == Booking::PATIENT_TYPE_IPD) {
                    $kid_check = User::getId("K-IPD", $i);
                }
                $booking_check = Booking::where('booking_kid', '=', $kid_check)->first();
                if($booking_check){
                    $limit = $i + 1;
                }else{
                    $final_kid = $i;
                    break;
                }   
            }


	if ((int)$final_kid <= 0) {
$final_kid = 1;

}		
            return (int) $final_kid;
        }
    }

    function generateBill(){
        $bill = new Bill();
        $bill->booking_id = $this->id;
        $bill->created_by = \Auth::user()->id;
        $bill->bill_no = Bill::getID();
        $bill->bill_date = date("d-m-Y");      
        $bill->consultation = $this->patient_type === self::PATIENT_TYPE_IPD ? 0 : $this->getConsultationAmountWithoutBill();
        $bill->room_rent =  $this->getAccomodationAmount(true, true);
        $bill->diet = $this->getDietAmountWithoutBill();
        $bill->treatments = $this->getTreatmentsAmountWithoutBill();
        $bill->lab = $this->getLabAmountWithoutBill();

        foreach(Department::all() as $department) {
            if ($department->title == 'Physiotherapy') {
                $bill->physiotherapy = $this->getTreatmentsAmountWithoutBill($department->id);
            }
            if ($department->title == 'Naturopathy and Yoga') {
                $bill->naturopathy_and_yoga = $this->getTreatmentsAmountWithoutBill($department->id);
            }
            if ($department->title = 'Ayurveda') {
                $bill->ayurveda = $this->getTreatmentsAmountWithoutBill($department->id);
            }
        }
        $bill->discount = $this->getDiscountsAmountWithoutBill(true);
        $bill->misc = $this->getMiscAmountWithoutBill();
       
        $bill->bill_amount = $this->getPaidAmountWithoutBill();
        $bill->remaining_amount = $this->getPendingAmountWithoutBill();
        $bill->advance_amount = $this->getPaidAmountWithoutBill(true);
        $bill->refundable_amount = $this->getRefundAmountWithoutBill();
        if($bill->save()) {
            if ($bill->id != null) {
                $bill->updateBillIds();
            }
        }
        return $bill;
    }

}
