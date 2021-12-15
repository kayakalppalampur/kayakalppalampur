<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    //
    const TYPE_INTERNAL = 0;
    const TYPE_EXTERNAL = 1;

    const CATEGORY_Haematology = 1;
    const CATEGORY_Serology = 2;
    const CATEGORY_Biochemistry  = 3;

    protected $fillable = [
        'created_by',
        'name',
        'department_id',
        'price',
        'type',
        'category_id',
        'duration'
    ];

    protected $table = 'lab_tests';

    public static function getRules()
    {
        return [
            'name' => 'required',
            'department_id' => 'required',
        ];
    }

    public static function getTests()
    {
        $lab_tests = LabTest::where('department_id', \Auth::user()->department->department_id)->orWhere('department_id', 0)->get();
        if ($lab_tests->count() > 0) {
            return $lab_tests;
        }
        return [];
    }

    public static function getTypeDropDownList()
    {
        $list = [];
        foreach (self::getTypeOptions() as $id => $type) {
            $list[] = [
                'value' => $id,
                'show' => $type,
            ];
        }
        return $list;
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::TYPE_INTERNAL => 'Internal',
            /*self::TYPE_EXTERNAL => 'External',*/
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public static function getCategoryOptions($id = null)
    {
        $list = [
            self::CATEGORY_Haematology => 'Haematology ',
            self::CATEGORY_Serology => 'Serology ',
            self::CATEGORY_Biochemistry => 'Biochemistry',
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public function department()
    {
        return $this->belongsTo("App\Department", "department_id");
    }

    public function setData($request)
    {
        $this->created_by = \Auth::user()->id;
        $this->name = $request->get('name');
        $this->type = $request->get('type');
        $this->price = $request->get('price') ? $request->get('price') : 0;
        $this->department_id = $request->get('department_id');
        $this->duration = $request->get('duration');
        $this->category_id = $request->get('category_id');
        return $this;
    }

    public function patientLabTests()
    {
        return $this->hasMany('App\PatientLabTest', 'test_id');
    }

    public function customDelete()
    {
        foreach ($this->patientLabTests as $patient) {
            $patient->delete();
        }
        $this->delete();
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::lab-tests',
            'Laralum::lab-tests.print',
            'Laralum::lab-tests.export',
            'Laralum::lab-test.create',
            'Laralum::lab-test.edit',
            'Laralum::lab-test.delete'
        ];
    }

    public static function getCategoryDropdownList()
    {
        $models = self::getCategoryOptions();
        $uid = [];
        foreach ($models as $k => $v) {
            $uid[] = [
                'value' => $k,
                'show' => $v,
            ];
        }

        return $uid;
    }


}
