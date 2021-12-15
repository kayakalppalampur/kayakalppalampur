<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepartmentUser extends Model
{
    //
    protected $fillable = [
        'user_id',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    /*public static function whereUser_idAndDepartment_id($user_id, $department_id)
    {
        return self::where([
            'user_id' => $user_id,
            'department_id' => $department_id
        ]);
    }*/
}
