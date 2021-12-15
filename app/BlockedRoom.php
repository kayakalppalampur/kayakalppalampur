<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedRoom extends Model
{
    //
    const BLOCK_YEAR = 1;

    protected $fillable = [
        'room_id',
        'is_yearly',
        'blocked_months',
        'status',
        'created_by'
    ];

    public function room()
    {
        return $this->belongsTo('App\Room', 'room_id');
    }

    public static function rules()
    {
        return [
            'room_id' => 'required'
        ];
    }

    public static function getRoomIds($month)
    {
        $blocked_rooms = BlockedRoom::where("blocked_months", "LIKE", "%" . $month . "%")->get();
        $b_ids = [];
        foreach ($blocked_rooms as $blocked_room) {
            $b_ids[] = $blocked_room->room_id;
        }
        return $b_ids;
    }

    public static function deleteOld()
    {
        $blocked_rooms = BlockedRoom::all();
        $b_ids = [];
        foreach ($blocked_rooms as $blocked_room) {
            $blocked_room->delete();
        }
    }
}
