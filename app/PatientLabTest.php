<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientLabTest extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;
    
    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
        'lab_name',
        'date',
        'address',
        'test_id',
        'note',
        'department_id',
        'created_by',
        'patient_id',
        'status',
        'booking_id',
        'price',
        'test_status',
        'lab_report',
        'report_type'
    ];


    protected $table = 'patient_lab_tests';
    protected $appends = [
        'date_date',
    ];

    public function getDateDateAttribute()
    {
        return date("d-m-Y", strtotime($this->date));
    }


    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function booking()
    {
        return $this->belongsTo("App\Booking", "booking_id");
    }

    public function department()
    {
        return $this->belongsTo("App\Department", "department_id");
    }

    public function test()
    {
        return $this->belongsTo("App\LabTest", "test_id");
    }

    public function getPrice($discharge = false)
    {
        if ($discharge == false) {
            return $this->price;
        }
        if ($this->date <= date("Y-m-d")) {
            return $this->price;
        }
        
        return 0;
    }
    public static function getRules()
    {
        return [
            'date' => 'required',
           // 'test_id' => 'required',
           /* 'lab_name' => 'required',*/
        ];
    }
    /*public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->lab_name = $request->get('lab_name') ? $request->get('lab_name') : '';
        $this->date = date("Y-m-d", strtotime($request->get('date')));
        $test_ids =  $request->get('test_id');

        if ($test_ids) {
            $test_ids = is_array($test_ids) ? implode(',',$test_ids) : $test_ids;
        }

        if ($request->get('lab_test_name')) {
            $lab_test_id = self::getTestId($request->get('lab_test_name'));
            $test_ids = $lab_test_id.','.$lab_test_id;
        }

        $this->test_id = $test_ids;
        $price = 0;

        $test_models = LabTest::whereIn('id', explode(',',$test_ids))->get();

        foreach ($test_models as $test_model) {
            $price += $test_model->price;
        }

        $this->price = $price;

        $this->department_id = \Auth::user()->department->department_id;
        $this->note = $request->get('note');
        $this->address = $request->get('address');
        $this->status = $request->get('status') != null ? $request->get('status') : self::STATUS_PENDING;
        return $this;
    }*/



    public function setData($request,$test_id)
    {
        \Log::info('test_id:: '.$test_id);
        $price = 0;
        $test_model = LabTest::where('id', $test_id)->get();
        $price = $test_model[0]->price;
        $this->test_id = $test_id;
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->lab_name = $request->get('lab_name') ? $request->get('lab_name') : '';
        $this->date = date("Y-m-d", strtotime($request->get('date')));
        $this->price = $price;
        $this->department_id = \Auth::user()->department->department_id;
        $this->note = $request->get('note');
        $this->address = $request->get('address');
        $this->status = $request->get('status') != null ? $request->get('status') : self::STATUS_PENDING;
        return $this;
    }


   /* public function setData($request)
    {
       // \Log::info('test_id:: '.$test_id);
        $test_ids = $request->get("test_id");
        foreach($test_ids as $test_id){
            \Log::info('test_id:: '.$test_id);
            $price = 0;
            $test_model = LabTest::where('id', $test_id)->get();
            $price = $test_model[0]->price;
            $this->test_id = $test_id;
            $this->booking_id = $request->get("booking_id");
            $this->patient_id = $request->get("patient_id");
            $this->created_by = \Auth::user()->id;
            $this->status = self::STATUS_PENDING;
            $this->lab_name = $request->get('lab_name') ? $request->get('lab_name') : '';
            $this->date = date("Y-m-d", strtotime($request->get('date')));
            $this->price = $price;
            $this->department_id = \Auth::user()->department->department_id;
            $this->note = $request->get('note');
            $this->address = $request->get('address');
            $this->status = $request->get('status') != null ? $request->get('status') : self::STATUS_PENDING;
            $this->save();
        }
        
        return "done";
    }*/

    public static function getTestId($name)
    {
        $test = LabTest::where('name', $name)->where('department_id', \Auth::user()->department->department_id)->first();
        if ($test == null) {
            $test = new LabTest();
            $test->name = $name;
            $test->department_id = \Auth::user()->department->department_id;
            $test->created_by = \Auth::user()->id;
            $test->price = 0;
            $test->save();
        }

        return $test->id;
    }

    public function isEditable()
    {
        if ($this->created_by == \Auth::user()->id) {
            return true;
        }

        return false;
    }

    public static function discharge($id, $b_id, $status)
    {
        $tests = PatientLabTest::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($tests as $test) {
            $test->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $tests = PatientLabTest::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($tests as $test) {
            $test->delete();
        }
    }

    public function getTestsName()
    {
        $test_models = LabTest::whereIn('id', explode(',',$this->test_id))->get();

        $name = [];
        foreach ($test_models as $test_model) {
            $name[] = $test_model->name;
        }

        return implode(',', $name);
    }

    public function getAllPrice()
    {
        $test_models = LabTest::whereIn('id', explode(',',$this->test_id))->get();

        $price = 0;
        foreach ($test_models as $test_model) {
            $price += $test_model->price;
        }

        return $price;
    }

    public function checkTest($id)
    {
        $lab_tests = explode(',',$this->test_id);
        if (in_array($id, $lab_tests)) {
            return true;
        }

        return false;
    }

}
