<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'incharge_id'
    ];

    public static function getRules()
    {
        return [
            'title' => 'required'
        ];
    }

    public static function getAyurvedId()
    {
        $department = Department::whereIn('title',['Panchakarma', 'Ayurveda'])->first();
        if ($department != null)
            return $department->id;
        return 0;
    }

    public static function getDropDownList($id = null)
    {
        $departments = self::all();
        $uid = [];
        foreach ($departments as $department) {
            $uid[] = [
                'value' => $department->id,
                'show' => $department->title,
            ];
        }

        return $uid;
    }

    public function users()
    {
        return $this->hasMany('App\DepartmentUser', 'department_id');
    }

    public function treatments()
    {
        return $this->hasMany('App\Treatment', 'department_id');
    }

    public function setData($request)
    {
        $this->title = $request->get('title');
        $this->description = $request->get('description');
        $this->incharge_id = $request->get('incharge_id') ? $request->get('incharge_id') : "";
        $this->color = $request->get('color');
        return $this;
    }

    public function isAllowed()
    {
        if ($this->users == null) {
            return true;
        }

        return false;
    }

    public function getLastTokenNo()
    {
        $date = (string)date('Y-m-d');
        $patient_token = PatientToken::where(\DB::raw('Date(start_date)'), $date)->where('department_id', $this->id)->orderBy('created_at', 'DESC')->first();
        if ($patient_token != null)
            return $patient_token->token_no;
        return 0;
    }

    public function customDelete()
    {
        $users = $this->users->count();
        if ($users > 0) {
            return false;
        } else {
            $this->delete();
        }
        return true;
    }

    public function getDoctors()
    {
        $users = $this->users;
        $role_user = [];
        foreach ($users as $user) {
            if (isset($user->user->userRole->role_id)) {
                if ($user->user->userRole->role_id == Role::getDoctorId()) {
                    $role_user[] = $user->user_id;
                }
            }
        }

        $users = User::whereIn('id', $role_user)->get();
        return $users;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::departments',
            'Laralum::department_create',
            'Laralum::department_edit',
            'Laralum::department.view',
            'Laralum::department.send_reply',
            'Laralum::department_delete',
        ];
    }
}
