<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'name', 'number_of_floors','status', 'description', 'room_types'
    ];
    protected $table = 'buildings';

    public function rooms()
    {
        return $this->hasMany('App\Room', 'building_id');
    }

    public static function getBuildingOptions()
    {
        $buildings = self::pluck('name', 'id');
        return $buildings->toArray();
    }

    public static function getFirstId()
    {
        $id = 0;
        $first = self::first();
        if ($first != null) {
            $id = $first->id;
        }
        return $id;
    }

    public static function getFloors($id = null)
    {
        if ($id === null) {
            $id = self::getFirstId();
        }

        $number_of_floors = '';
        if(isset($id) && !empty($id))
        {
            if (Building::where('id', $id)->exists()) {
                $building_arr = Building::find($id,['number_of_floors']);
                $number_of_floors = $building_arr->number_of_floors;
            }
        }

        return $number_of_floors;
    }

    public static function getFloorOptions($id = null)
    {
        $floors = self::getFloors($id);
        $floor_ar = range(1,$floors);
        $floor_id_ar = [];
        foreach ($floor_ar as $floor) {
            $floor_id_ar[$floor] = self::getFloorName($floor);
        }
        return $floor_id_ar;
    }

    public static function getRoomOptions($id, $floor, $gender = null)
    {
        $rooms = Room::where([
            'building_id' => $id,
            'floor_number' => $floor
        ])/*->
        where(function($query) {
            $query->where('is_blocked', Room::IS_AVAILABLE)->orWhereNull('is_blocked');
        })*/;

        /*if ($gender != null) {
            $rooms = $rooms->whereIn('gender',[$gender, Room::GENDER_NA]);
        }else{
            $rooms = $rooms->where('gender', Room::GENDER_NA);
        }*/
        
        $rooms = $rooms->get();
       // print_r($rooms);exit;
        
        return $rooms;
    }

    public static function getBedOptionsArray($id, $floor, $check_in_date, $check_out_date, $booking_type, $gender = null)
    {
        $rooms = Room::where([
            'building_id' => $id,
            'floor_number' => $floor
        ])/*->
        where(function($query) {
            $query->where('is_blocked', Room::IS_AVAILABLE)->orWhereNull('is_blocked');
        })*/;
        if ($gender != null) {
            $rooms = $rooms->whereIn('gender',[$gender, Room::GENDER_NA]);
        }else{
            $rooms = $rooms->where('gender', Room::GENDER_NA);
        }

        $room = $rooms->first();
        if ($room == null) {
            $room = new Room;
        }
        $bed_status_ar = Room::getBedOptionsArray($room->id, $check_in_date, $check_out_date, $booking_type, $gender = null);
        return $bed_status_ar;
    }


    public static function getRoomOptionsArray($building_id, $floor, $check_in_date, $check_out_date, $type, $gender, $booking_id, $is_member)
    {
        $rooms = self::getRoomOptions($building_id, $floor);
        $arr = [];
        foreach ($rooms as $room) {
            $blocked_room = BlockedRoom::where('room_id', $room->id)->first();
            if ($blocked_room != null) {
                if ($blocked_room->is_yearly != BlockedRoom::BLOCK_YEAR) {
                    $month = date("M", strtotime($check_in_date));
                    if (strpos($month, $blocked_room->blocked_months) == false) {
                        if ($room->checkBooking($check_in_date, $check_out_date, $type, $gender, $booking_id, $is_member)) {
                            $price = $room->room_price;
                            if ($type == Booking::BOOKING_TYPE_SINGLE_BED) {
                                $price = $room->bed_price;
                            }
                            $arr[$room->id] = [
                                'id' => $room->id,
                                'price' => $price,
                                'number' => $room->room_number
                            ];
                        }
                    }
                }
            }
        }

        return $arr;
    }

    public static function getFloorName($num)
    {
        $first_word = array('eth','Ground', 'First','Second','Third','Fouth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Elevents','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
        $second_word =array('','','Twenty','Thirty','Forty','Fifty', 'Sixty', 'Seventy');

        if ($num == 0)
            return 'Ground Floor';

        if($num <= 20)
            return $first_word[$num].' Floor';

        $first_num = substr($num,-1,1);
        $second_num = substr($num,-2,1);

        $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
        return $string.' Floor';
    }

    public static function getRoomTypes($id)
    {
        $building = self::find($id);
        $types = explode(',', $building->room_types);
        $list = [];
        foreach ($types as $type) {
            $room_type = Room_Type::find($type);
            if ($room_type != null) {
                $list[$room_type->id] = $room_type->name;
            }
        }
        return $list;
    }

    public function isRoomTypeChecked($type)
    {
        $types = explode(',', $this->room_types);
        if (in_array($type, $types)) {
            return 'checked';
        }
        return '';
    }

    public function getBedCount()
    {
        $rooms = Room::where('building_id', $this->id)->get();
        $beds = 0;
        foreach ($rooms as $room) {
            if ($room->bed_count > $beds)
                $beds = $room->bed_count;
        }
        return $beds;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::building.create',
            'Laralum::buildings',
            'Laralum::building.edit',
            'Laralum::building.delete'
        ];
    }

    public static function getChartArray()
    {
        return [
            'Laralum::accommodation.roomStatus'
        ];
    }

    public static function getRoomTypeArray()
    {
        return [
            'Laralum::room_type.create',
            'Laralum::room_types',
            'Laralum::room_type.delete',
            'Laralum::room_type.edit',
        ];
    }

    public static function getRoomArray()
    {
        return [
            'Laralum::room.create',
            'Laralum::rooms',
            'Laralum::room.delete',
            'Laralum::room.edit'
        ];
    }

    public static function getServicesArray()
    {
        return [
            'Laralum::room.services',
            'Laralum::external_services',
            'Laralum::external_service.create',
            'Laralum::external_service.edit',
            'Laralum::external_service.delete'
        ];
    }

    public static function getBlockedRoomArray()
    {
        return [
            'Laralum::block-rooms',
        ];
    }

    public function customDelete()
    {
        $rooms = $this->rooms;
        foreach ($rooms as $room) {
            $room->customDelete();
        }
        $this->delete();
    }

    public function getMaleCount($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $building_id = $this->id;

        $booking_rooms = BookingRoom::whereHas('room', function($q) use($building_id) {
            $q->where('building_id', $building_id);
        })->whereHas('booking', function($q) {
            $q->whereHas('userProfile', function($q) {
              $q->where('gender', UserProfile::GENDER_MALE);
            });
        })->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->count();

        return $booking_rooms;
    }

    public function getFemaleCount($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $building_id = $this->id;

        $booking_rooms = BookingRoom::whereHas('room', function($q) use($building_id) {
            $q->where('building_id', $building_id);
        })->whereHas('booking', function($q) {
            $q->whereHas('userProfile', function($q) {
                $q->where('gender', UserProfile::GENDER_FEMALE);
            });
        })->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->count();

        return $booking_rooms;
    }

    public function getBookings($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }
        $building_id = $this->id;
        $booking_rooms = BookingRoom::whereHas('room', function($q) use($building_id) {
            $q->where('building_id', $building_id);
        })->whereDate('check_in_date', '<=', $date)->whereDate('check_out_date', '>=', $date)->get();

        return $booking_rooms;
    }

    public function checkDelete()
    {
        $building_id = $this->id;

        $booking_rooms = BookingRoom::whereHas('room', function($q) use($building_id) {
            $q->where('building_id', $building_id);
        })->exists();

        return $booking_rooms;
    }
}
