<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    const STATUS_NOT_SET = 0;
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 2;
    const STATUS_LEAVE = 3;

    protected $fillable = [
        'user_id',
        'date_in',
        'time_in',
        'time_out',
        'status',
        'comment',
        'type',
        'created_by',
        'leave_id'
    ];


    public function user()
    {
        return $this->belongsTo('\App\Staff', 'user_id','id');
    }

    public function setData($data, $id = null) {

        $data = !is_object($data) ? (object) $data : $data;

        $data = new Collection($data);

        $this->user_id      =   $data->get('user_id');
        $this->date_in      =   $data->get('date_in') != null ? date('Y-m-d', strtotime($data->get('date_in'))) : date('Y-m-d');
        $this->time_in      =   $data->get('time_in') != null ? date('H:i:s', strtotime($data->get('time_in'))) : date('H:i:s');
        $this->time_out     =   date('H:i:s', strtotime($data->get('time_out')));
        $this->status       =   $data->get('status');

        if($this->status == self::STATUS_ABSENT) {
            $this->time_in = "00:00:00";
            $this->time_out = "00:00:00";
        }

        $this->comment      =   $data->get('comment');
        $this->created_by   =   $data->get('created_by') != null ? $data->get('created_by') : \Auth::user()->id;
        $this->type         =   $data->get('type');

        return true;
    }

    /**
     * create validation rules
     * @return array
     */
    public static function getRules() {
        $rules = [
            'user_id'       =>  'required',
            //'date_in'       =>  'required',
          //  'time_in'       =>  'required',
            'status'        =>  'required',
          //  'created_by'    =>  'required',
        ];
        return $rules;
    }

    /**
     * search attendance data from column
     * @param $column
     * @param $value
     * @return mixed
     */
    public static function getAttendanceFromColumn($column, $value){

        $attendances    =   Attendance::where($column,$value)->paginate(10);
        return $attendances;

    }

    /**
     * get attendance data of all users
     * @param $date
     * @return mixed
     */
    public static function getAttendances($date = null)
    {
        $attendances = User::join('attendances', 'attendances.user_id', '=', 'users.id')->orderBy('attendances.date_in','DESC');

        if ($date == null) {
            $attendances = $attendances->where('attendances.date_in', $date);
        }

        $attendances = $attendances->get();

        return $attendances;
    }

    public static function getStatusOptions($id = null) {
        $list = [
            self::STATUS_NOT_SET => 'Not set',
            self::STATUS_LEAVE => 'On Leave',
            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',
        ];

        if($id === null)
            return $list;

        return $list[$id];
    }

    public static function getStatusLabelOptions($id) {
        switch ($id) {
            case self::STATUS_LEAVE : return '<div class="ui orange label">'.self::getStatusOptions($id).'</div>';
                break;
            case self::STATUS_PRESENT : return '<div class="ui green label">'.self::getStatusOptions($id).'</div>';
                break;
            case self::STATUS_ABSENT :
            default :
            return '<div class="ui red label">'.self::getStatusOptions($id).'</div>';
                break;
        }
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::attendances',
            'Laralum::attendances.print',
            'Laralum::attendance.export',
            'Laralum::attendance.search',
            'Laralum::attendance.create',
            'Laralum::attendance.create.date',
            'Laralum::attendance.add_leave',
            'Laralum::attendance.add_leave_date',
            'Laralum::attendance.add_leave_any',
            'Laralum::attendance.listLeaves',
            /*'Laralum::attendance.leaves',*/
        ];
    }
}

