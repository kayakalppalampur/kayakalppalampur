<?php

namespace App;

use App\Http\Controllers\Laralum\Laralum;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const GENDER_NOT_SPECIFIED = 0;

    const UNMARRIED = 1;
    const MARRIED = 2;

    protected $fillable = [
        'name',
        'gender',
        'marital_status',
        'date_of_birth',
        'address',
        'department',
        'contact_no',
        'contact_email',
        'created_by',
        'status',
        'user_id'
    ];
    protected $table = 'staff';

    public static function rules()
    {
        return [
            'name' => 'required',
            'department' => 'required'
        ];
    }

    public static function getGenderOptions($id = null)
    {
        $list = [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male',
        ];

        if ($id === null)
            return $list;

        $list[self::GENDER_NOT_SPECIFIED] = 'Not Specified';

        return $list[$id];
    }

    public static function getMaritalStatus($id = null)
    {
        $list = [
            self::MARRIED => 'Married',
            self::UNMARRIED => 'Unmarried'
        ];

        if ($id === null)
            return $list;

        if (isset($list[$id]))
            return $list[$id];

        return $id;
    }

    public function setData($request)
    {
        $this->name = $request->get('name');
        $this->gender = $request->get('gender');
        $this->marital_status = $request->get('marital_status');
        $this->date_of_birth = $request->get('date_of_birth');
        $this->address = $request->get('address');
        $this->department = $request->get('department');
        $this->contact_no = $request->get('contact_no');
        $this->contact_email = $request->get('contact_email');
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_ACTIVE;
        return $this;
    }

    public function staffDepartment()
    {
        return $this->belongsTo('App\StaffDepartment', 'department');
    }
    public function getuser_name($id){
        $staff_user = Staff::get($id);
         return $staff_user->name;
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance', 'user_id');
    }

    public function attendance($date, $status = null)
    {
        $attendance = Attendance::where('user_id', $this->id)->where('date_in', $date);

        if ($status != null) {
            $attendance = $attendance->where('status', $status);
        }
        $attendance = $attendance->first();

        if ($attendance != null) {

            $label = Attendance::getStatusLabelOptions($attendance->status);

            if ($attendance->status == Attendance::STATUS_LEAVE) {
                $label .= ' <i class="fa fa-question" style="cursor:pointer;" id="comment_' . $this->id . '"title="' . $attendance->comment . '"></i>';
            }
            $time_in = date('H:i', strtotime($attendance->time_in));
            $time_out = date('H:i', strtotime($attendance->time_out));
            $label .= '<input type="hidden" id="time_in_val_' . $this->id . '" value=' . $time_in . '>';
            $label .= '<input type="hidden" id="time_out_val_' . $this->id . '" value=' . $time_out . '>';
            $label .= '<input type="hidden" id="selected_state_' . $this->id . '" value=' . $attendance->status . '>';

            if (Laralum::loggedInUser()->hasPermission('attendance.edit') && ($date <= date('Y-m-d') || $attendance->status == Attendance::STATUS_LEAVE)) {
                $label .= ' <i id="edit_' . $this->id . '" class="fa fa-edit hover"></i>';
            }

            return $label;
        } elseif ($date > date('Y-m-d')) {
            return "Not Set";
        }

        return false;
    }

    public function saveLeave($date, $comment)
    {
        $date_ar = explode(',', $date);

        foreach ($date_ar as $date) {
            $leave = Attendance::where([
                'user_id' => $this->id,
                'date_in' => $date,
            ])->first();
            if ($leave == null) {
                $leave = new Attendance();
            }

            $leave->user_id = $this->id;
            $leave->date_in = $date;
            $leave->time_in = "00:00:00";
            $leave->time_out = "00:00:00";
            $leave->status = Attendance::STATUS_LEAVE;
            $leave->comment = $comment;
            $leave->save();
        }

        return true;
    }

    public function getLeaveDates()
    {
        $leaves = Attendance::where('user_id', $this->id)->where('date_in', '>=', (string)date("Y-m-d"))->where('status', Attendance::STATUS_LEAVE)->get();

        $leave_dates = "";
        foreach ($leaves as $leave) {
            $leave_dates .= $leave->date_in . '<br/>';
        }
        return $leave_dates;
    }

    public function customDelete()
    {
        $attendances = $this->attendances;
        foreach ($attendances as $attendance) {
            $attendance->delete();
        }
        $this->delete();
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::staff',
            'Laralum::staff.search',
            'Laralum::staff.add',
            'Laralum::staff.edit',
            'Laralum::staff.delete'
        ];
    }

    public static function importData($arr, $dept)
    {
        $model = Staff::where('name', $arr['name'])->where('department', $dept)->first();

        if ($model == null) {
            $model = new Staff();
        }

        $model->name = $arr['name'];
        $model->department = $dept;
        $model->created_by = \Auth::user()->id;
        $model->status = self::STATUS_ACTIVE;
        $model->save();
    }
}

