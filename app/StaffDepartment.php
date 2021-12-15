<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffDepartment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status'
    ];
    protected $table = 'staff_departments';

    public static function rules()
    {
        return [
            'title' => 'required'
        ];
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

    public static function getDeptId($title)
    {
        $dept = StaffDepartment::where('title', 'LIKE', '%' . $title . '%')->first();

        if ($dept != null) {
            return $dept->id;
        }

        $dept = StaffDepartment::create([
            'title' => $title
        ]);
        return $dept->id;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.staff_departments',
            'Laralum::admin.staff_departments.search',
            'Laralum::admin.staff_departments.add',
            'Laralum::admin.staff_departments.add',
            'Laralum::admin.staff_departments.edit',
            'Laralum::admin.staff_departments.update',
            'Laralum::staff_departments.delete',
        ];
    }

    public function users()
    {
        return $this->hasMany('App\Staff', 'department');
    }

    public function setData($request)
    {
        $this->title = $request->get('title');
        $this->description = $request->get('description');
        return $this;
    }

    public function customDelete()
    {
        $users = $this->users->count();
        if ($users > 0) {
            return false;
        }
        $this->delete();
        return true;
    }
}
