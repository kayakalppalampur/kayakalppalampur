<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ROLE_PATIENT = 100;
    const ROLE_ADMIN = 1;
    const ROLE_DOCTOR = 5;

    public function users()
    {
    	return $this->belongsToMany('App\User');
    }

    public function permissions()
    {
    	return $this->belongsToMany('App\Permission');
    }

    public function hasPermission($slug)
    {
        foreach($this->permissions as $perm) {
            if($perm->slug == $slug) {
                return true;
            }
        }
        return false;
    }

    public static function getPatientId()
    {
        $role = Role::where('name', 'Patient')->first();
        if ($role != null) {
            return $role->id;
        }
        return self::ROLE_PATIENT;
    }

    public static function getDoctorId()
    {
        $role = Role::where('name', 'Doctor')->first();
        if ($role != null) {
            return $role->id;
        }
        return self::ROLE_DOCTOR;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::roles',
            'Laralum::roles_create',
            'Laralum::roles_show',
            'Laralum::roles_edit',
            'Laralum::roles_permissions',
            'Laralum::roles_delete',
            ''
        ];
    }
}
