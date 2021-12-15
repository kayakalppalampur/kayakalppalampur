<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalService extends Model
{
    protected $fillable = [
        'name', 'description','status', 'price', 'room_id'
    ];

    protected $table = 'external_services';

    public static function getServices($room_id)
    {
        $room = Room::find($room_id);
        
        if ($room != null) {
            if ($room->services != null) {
                $ids = explode(',', $room->services);
                $services = self::whereIn('id', $ids)->get();
                return $services;
            }
        }

        return [];
    }

    public function checkDelete()
    {
        $service_id = $this->id;
        $booking_rooms = UserExtraService::where('service_id', $service_id)->exists();
        return $booking_rooms;
    }


    public function customDelete()
    {
        /*$booked_rooms = $this->bookedRooms;

        foreach ($booked_rooms as $booked_room) {
            $booked_room->customDelete();
        }*/

        $this->delete();
    }
}
