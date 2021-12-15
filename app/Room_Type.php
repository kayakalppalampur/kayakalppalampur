<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room_Type extends Model
{
    protected $fillable = [
        'name', 'price', 'short_name', 'status'
    ];
    protected $table = 'room_types';

    public function checkDelete()
    {
        $room_type_id = $this->id;
        $booking_rooms = BookingRoom::whereHas('room', function($q) use($room_type_id) {
            $q->where('room_type_id', $room_type_id);
        })->exists();
        return $booking_rooms;
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function customDelete()
    {
        $rooms = $this->rooms;

        foreach ($rooms as $room) {
            $room->customDelete();
        }
        $this->delete();
    }
}
