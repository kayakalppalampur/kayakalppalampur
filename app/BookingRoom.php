<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\EventDispatcher\Tests\Service;

class BookingRoom extends Model
{
    const BOOKING_TYPE_SINGLE_BED = 0;
    const BOOKING_TYPE_SINGLE_OCCUPANCY = 1;
    const BOOKING_TYPE_EXTRA_BED = 2;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'room_id',
        'bed_number',
        'member_id',
        'booking_id',
        'check_in_date',
        'check_out_date',
        'status',
        'type',
        'services',
        'price',
        'user_id'
    ];

    protected $table = 'booking_rooms';

    protected $appends = [
        'alloted_to',
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

    public function getAllotedToAttribute()
    {
        if ($this->member != null) {
            return $this->member->name . ' (Member)';
        }

        return $this->booking->userProfile->first_name . ' ' . $this->booking->userProfile->last_name;
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function member()
    {
        return $this->belongsTo('App\Member', 'member_id');
    }

    public function room()
    {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public function userServices()
    {
        return $this->hasMany('App\UserExtraService', 'booking_id');
    }

    public static function getRelationsList()
    {
        return [
            'booking',
            'booking.userProfile',
            'member',
            'room',
            'userServices'
        ];
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'booking_id' => 'required',
            'check_in_date' => 'required',
            'check_out_date' => 'required',
            'room_id' => 'required',
        ];
    }

    public function roomDetails()
    {
        $type = BookingRoom::getBookingType($this->type);
        if ($this->room != null) {
            $price = $this->room->room_price;
            if ($this->type == Booking::BOOKING_TYPE_SINGLE_BED) {
                $price = $this->room->bed_price;
            }
            if (isset($this->room->building->name)) {
                return $this->room->building->name . '-' . $this->room->getFloorNumber($this->room->floor_number) . '-' . $this->room->room_number . '(' . $type . '-' . $price . '/day)';
            }
        }
        return "";
    }

    public function serviceDetails()
    {
        $html = "";
        $services = $this->userServices;
       
        if (!empty($services)) {
            foreach ($services as $service) {
                $now = date("Y-m-d");    
                $driver_stay = $service->driver_stay;
                $start = $service->service_start_date;
                $end = $service->service_end_date;
                if($driver_stay == null){
                        $html .= $service->service_name . '(Rs.' . $service->price . '/day) From ' . date('d-m-Y', strtotime($service->service_start_date)) . ' to ' . date('d-m-Y', strtotime($service->service_end_date)) . '<br>';
                }
                else{
                    if($driver_stay == 1){
                        $html .= $service->service_name . ' Stay Inside <br>';
                    }
                    elseif($driver_stay == 2){
                        $html .= $service->service_name . '(Rs.' . $service->price . '/day) From ' . date('d-m-Y', strtotime($service->service_start_date)) . ' to ' . date('d-m-Y', strtotime($service->service_end_date)) . '<br>';
                    }
                }
                       
                
            }
        }

        return $html;
    }


    public function extraserviceDetails()
    {
        $services = $this->userServices;
        $array = array();
        if (!empty($services)) {
            foreach ($services as $service) {
                $array[] = $service->is_child_driver;
            }
        }

        return $array;
    }

    public function getPrice($room_id = null)
    {

        return $this->price;
        /*
        $room = Room::find($room_id);
        if ($room_id == null) {
            $room = $this->room;
        }
        $price = 0;
        if ($room != null) {
            $price = $this->room->room_price;
            $type = $this->type;

            if ($type == self::BOOKING_TYPE_SINGLE_BED) {
                $price = $this->room->bed_price;
            }
        }*/

        return $price;
    }

    public function getAllServices()
    {
        $services = UserExtraService::where('booking_id', $this->id)->get();
        return $services;
    }

    public function allDaysPrice($room_id = null, $ser = true, $current = false)
    {
        $price = $this->getPrice($room_id);

         $now = strtotime($this->check_in_date); // or your date as well
       //echo "<br>";
         $your_date = strtotime($this->check_out_date);

        if (date("Y-m-d", strtotime($this->check_in_date)) <= date("Y-m-d")) {
            if ($your_date >= strtotime(date("Y-m-d")) && $current == true) {
                $your_date = strtotime("today");
            }
        }else{
            if($current == true) {
                return 0;
            }
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
                    $service_price += $service->daysPrice($current);
                }
            }
            $total = $booking_price + $service_price;
        }

        return $total;
    }

    public function daysPrice($room_id = null, $ser = true)
    {

        /*$thischeckin = $this->check_in_date;
        $thischeckout = $this->check_out_date;
        if($thischeckin != null && $thischeckout != null){

        }*/
        $price = $this->getPrice($room_id);
        $now = strtotime($this->check_in_date); // or your date as well
        $your_date = strtotime($this->check_out_date);

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


    public function saveServices($services, $start_date, $end_date, $member_id,$booking_room_id)
    {
        $services = array_filter(!is_array($services) ? explode(',', $services) : $services);
        if ($services != null) {

            foreach ($services as $k => $service) {
                $service_model = ExternalService::find($service);
                $now = date("Y-m-d");
                if (!empty($start_date[$k]) && !empty($end_date[$k])) {
                    $is_exist = UserExtraService::where('booking_id',$booking_room_id)->where('member_id',$member_id)->where('service_id',$service)->first();
                    //return count($is_exist);
                    if($is_exist){
                        $servicecheckindate = $is_exist->service_start_date;
                        $servicecheckoutdate = $is_exist->service_end_date;

                        if($now > $servicecheckindate){
                            $re_check_in = date("Y-m-d", strtotime($start_date[$k]));
                            $old_checkout = date("Y-m-d", strtotime($is_exist->service_end_date));

                            if ($old_checkout > $re_check_in) {
                                $is_exist->update([
                                    'service_end_date' => date("Y-m-d", strtotime($start_date[$k]))
                                ]);
                            }

                            $new_service = new UserExtraService();
                        }
                        else{
                            $new_service = $is_exist;
                        }   
                        
                    }
                    else{
                        $new_service = new UserExtraService();
                    }
                    $new_service->user_id = $this->booking->user_id;
                    $new_service->member_id = $member_id;
                    $new_service->booking_id = $this->id;
                    $new_service->service_start_date =  date("Y-m-d", strtotime($start_date[$k]));
                    $new_service->service_end_date =  date("Y-m-d", strtotime($end_date[$k]));
                    $new_service->service_id = $service;
                    $new_service->price = $service_model->price;

                    if($new_service->id == null){
                        if($new_service->service_start_date < $new_service->service_end_date){
                             $new_service->save();
                        }
                    }
                    else{
                        $new_service->save();
                    }
                    
                }
            }
            
            $otherservices = UserExtraService::whereNotIn('service_id', $services)->where('booking_id',$booking_room_id)->where('member_id',$member_id)->get();

            if($otherservices->count() > 0){
                foreach($otherservices as $otherservice){
                    $startdate = $otherservice->service_start_date;
                    $now = date("Y-m-d");
                    if($now > $startdate){
                        $re_check_in = date("Y-m-d", strtotime($startdate));
                        $old_checkout = date("Y-m-d", strtotime($otherservice->service_end_date));

                        if ($old_checkout > $re_check_in) {
                            $otherservice->update([
                                'service_end_date' => date("Y-m-d", strtotime($startdate))
                            ]);
                        }
                    }
                    else{
                        $otherservice->delete();
                    }
                    
                }  
            }
        }
        else{
            $otherservices = UserExtraService::where('booking_id',$booking_room_id)->where('member_id',$member_id)->get();
            if($otherservices->count() > 0){
                foreach($otherservices as $otherservice){
                    $startdate = $otherservice->service_start_date;
                    $now = date("Y-m-d");
                    if($now > $startdate){
                        $re_check_in = date("Y-m-d", strtotime($startdate));
                        $old_checkout = date("Y-m-d", strtotime($otherservice->service_end_date));

                        if ($old_checkout > $re_check_in) {
                            $otherservice->update([
                                'service_end_date' => date("Y-m-d", strtotime($startdate))
                            ]);
                        }
                    }
                    else{
                        $otherservice->delete();
                    }
                    
                }  
            }  
        }
    }

    public function deleteServices($member_id = null)
    {

        if ($member_id != null) {
            return $this->id;
            return $user_services = UserExtraService::where([
                'member_id' => $member_id,
                'booking_id' => $this->id,
            ])->get();
            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service->delete();
                }
            }
        } else {
            $user_services = UserExtraService::where([
                'booking_id' => $this->id,
            ])->get();

            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service->delete();
                }
            }
        }
    }

    public function getServiceIdAr($member_id = null)
    {
        $user_service_ar = [];
        if ($member_id != null) {
            $user_services = UserExtraService::where([
                'user_id' => $this->user_id,
                'member_id' => $member_id,
                'booking_id' => $this->id,
            ])->get();
            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service_ar[] = $user_service->service_id;
                }
            }
        } else {
            $user_services = UserExtraService::where([
                'user_id' => $this->user_id,
                'booking_id' => $this->id,
            ])->get();
            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service_ar[] = $user_service->service_id;
                }
            }
        }
        return $user_service_ar;
    }


    public function cancelServices($member_id = null)
    {
        if ($member_id != null) {
            $user_services = UserExtraService::where([
                'user_id' => $this->user_id,
                'member_id' => $member_id,
                'booking_id' => $this->id,
            ])->get();
            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service->update([
                        'status' => UserExtraService::STATUS_CANCELLED
                    ]);
                }
            }
        } else {
            $user_services = UserExtraService::where([
                'user_id' => $this->user_id,
                'booking_id' => $this->id,
            ])->get();
            if ($user_services->count() > 0) {
                foreach ($user_services as $user_service) {
                    $user_service->update([
                        'status' => UserExtraService::STATUS_CANCELLED
                    ]);
                }
            }
        }
    }

    public static function getBookingType($id = null)
    {
        $list = [
            self::BOOKING_TYPE_SINGLE_BED => 'Single Bed',
            self::BOOKING_TYPE_SINGLE_OCCUPANCY => 'Single Occupancy',
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

    public function getName()
    {
        if ($this->member != null) {
            return $this->member->name;
        }

        return $this->booking->getProfile('first_name') . ' ' . $this->booking->getProfile('last_name');
    }

    public function checkIfPatient()
    {
        if ($this->member != null) {
            return "No";
        }

        return "Yes";
    }

    public function getRoomNumber()
    {
        $room = $this->room;
        $price = $this->room->room_price;
        if ($this->type == BookingRoom::BOOKING_TYPE_SINGLE_BED)
            $price = $this->room->bed_price;
        return $room->room_number . '(' . $room->getFloorNumber($room->floor_number) . '-' . $price . '/day)';
    }


    public static function getRoomIds($month_year)
    {
        $bookings = BookingRoom::whereNull('status')
            ->where(function ($query) use ($month_year) {
                $query->where(function ($query) use ($month_year) {
                    $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                })->orWhere(function ($query) use ($month_year) {
                    $query->where(\DB::raw('month(check_in_date)'), $month_year[0])->where(\DB::raw('year(check_in_date)'), $month_year[1]);
                });
            })->get();
        $b_ids = [];
        foreach ($bookings as $booking) {
            $b_ids[] = $booking->room_id;
        }
        return $b_ids;
    }

    public function customDelete()
    {
        $services = $this->userServices;
        foreach ($services as $service) {
            $service->delete();
        }
        $this->delete();
    }

    public static function checkBooking($checkin, $checkout, $id, $room_id)
    {
        $checkin = date("Y-m-d", strtotime($checkin));
        $checkout = date("Y-m-d", strtotime($checkout));
        $result = [
            'status' => 'success',
        ];

        $room = BookingRoom::/*where('booking_id', $id)->*/where(function ($q) use ($checkin, $checkout) {
            $q->orWhereBetween('check_in_date', [$checkin, $checkout])
                ->orWhereBetween('check_out_date', [$checkin, $checkout])
                ->orWhere(function ($q) use ($checkin, $checkout) {
                    $q->whereDate('check_in_date', '<=', $checkin)->whereDate('check_out_date', '>=', $checkin);
                })->orWhere(function ($q) use ($checkin, $checkout) {
                    $q->whereDate('check_in_date', '<=', $checkout)->whereDate('check_out_date', '>=', $checkout);
                });
        })->where('id', '!=', $room_id)->exists();

        if ($room) {
            $result = [
                'status' => 'error',
                'message' => 'Please choose another dates.'
            ];
        }

        return $result;
    }

    public function servicesCheck($id, $booking_id = null, $attr = null)
    {
        $result = null;
        if($booking_id != '' && $booking_id != null){
            $service = UserExtraService::where('booking_id', $booking_id)->where('service_id',$id)->first();
        }
        else{
            $service = UserExtraService::where('booking_id', $this->id)->where('service_id',$id)->first();
        }
        if ($service) {
            if ($attr == null) {
                $result = true;
            } else {
                if($attr == 'service_start_date') {
                    if ($service->$attr < date("Y-m-d")) {
                        $result = date("d-m-Y");
                    }else {
                        $result = date("d-m-Y", strtotime($service->$attr));
                    }
                }else if($attr == 'service_end_date') {
                    if ($service->$attr < date("Y-m-d", strtotime("+1 day"))) {
                        $result = date("d-m-Y", strtotime("+1 day"));
                    }else {
                        $result = date("d-m-Y", strtotime($service->$attr));
                    }
                }else {
                    $result = date("d-m-Y", strtotime($service->$attr));
                }


            }
        }

        return $result;
    }

    public function lastId()
    {
        $booking_rooms = BookingRoom::where('booking_id', $this->booking_id)->where('member_id', $this->member_id)->orderBy('id', 'DESC')->first();

       \Log::info('bookingroom'.print_r($booking_rooms, true));

        if ($booking_rooms) {
            return $booking_rooms->id;
        }

        return $this->id;
    }
}

