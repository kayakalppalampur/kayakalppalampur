<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    //

    const TYPE_SHORT_LEAVE = 3;
    const TYPE_HALF_DAY_LEAVE= 2;
    const TYPE_LEAVE = 1;

    protected $fillable = [
        'user_id',
        'date_start',
        'date_end',
        'status',
        'comment',
        'type',
        'created_by',
    ];

    protected $appends = [
        'date_start_date',
        'date_end_date'
    ];


    public function getDateStartDateAttribute()
    {
        return date("d-m-Y",strtotime($this->date_start));
    }

    public function getDateEndDateAttribute()
    {
        return date("d-m-Y",strtotime($this->date_end));
    }

    public function user()
    {
        return $this->belongsTo('\App\Staff', 'user_id','id');
    }

    public static function getStatusOptions($id = null) {
        $list = [
            self::TYPE_LEAVE => 'Leave',
            self::TYPE_HALF_DAY_LEAVE => 'Half Day Leave',
            self::TYPE_SHORT_LEAVE => 'Short Leave',
        ];

        if($id === null)
            return $list;

        return $list[$id];
    }

    public function saveLeave($date, $comment)
    {
        $date_ar = explode(',', $date);
        $l_ar = [];

        foreach ($date_ar as $date) {
            $leave = Attendance::where([
                'user_id' => $this->user_id,
                'date_in' => $date,
                'leave_id' => $this->id
            ])->first();

            if ($leave == null) {
                $leave = new Attendance();
            }

            $leave->user_id = $this->user_id;
            $leave->date_in = $date;
            $leave->time_in = "00:00:00";
            $leave->time_out = "00:00:00";
            $leave->status = Attendance::STATUS_LEAVE;
            $leave->comment = $comment;
            $leave->leave_id = $this->id;
            $leave->save();
            $l_ar[] = $leave->id;
        }


        $other_leaves = Attendance::where([
            'user_id' => $this->user_id,
            'leave_id' => $this->id
        ])->whereNotIn('id', $l_ar)->get();
        foreach ($other_leaves as $other_leav) {
            $other_leav->delete();
        }

        return true;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'leave_id');
    }

    public function customDelete()
    {
        $attendances = $this->attendances;

        foreach ($attendances as $attendance) {
            $attendance->delete();
        }

        $this->delete();
    }


    public function check($date_range_arr)
    {
        $leaves = Attendance::where('leave_id', '!=', $this->id)->whereIn('date_in', $date_range_arr)->count();

         if ($leaves > 0) {
             return false;
         }

        return true;

    }

}
