<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NeurologicalExamination extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;
    
    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
         'headache',
        'headache_doctor',
        'vision_hearing',
        'vision_hearing_doctor',
        'pain',
        'pain_doctor',
        'numbness',
        'numbness_doctor',
        'weakness',
        'weakness_doctor',
        'abnormal_movements',
        'abnormal_movements_doctor',
        'fits',
        'fits_doctor',
        'doctor_details',
        'booking_id'
    ];

    protected $table = 'neurological_examinations';

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->patient_id = $request->get("patient_id");
        $this->headache = $request->get('headache');
        $this->headache_doctor = $request->get('headache_doctor');
        $this->vision_hearing = $request->get('vision_hearing');
        $this->vision_hearing_doctor = $request->get('vision_hearing_doctor');
        $this->pain = $request->get('pain');
        $this->pain_doctor = $request->get('pain_doctor');
        $this->numbness = $request->get('numbness');
        $this->numbness_doctor = $request->get('numbness_doctor');
        $this->weakness = $request->get('weakness');
        $this->weakness_doctor = $request->get('weakness_doctor');
        $this->abnormal_movements = $request->get('abnormal_movements');
        $this->abnormal_movements_doctor = $request->get('abnormal_movements_doctor');
        $this->fits = $request->get('fits');
        $this->fits_doctor = $request->get('fits_doctor');
        $this->doctor_details = $request->get('neuro_doctor_details');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->update([
                'status' =>$status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $models = self::where('patient_id', $id)->where(['booking_id' => $b_id])->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function getValue($attr)
    {
        if ($this->$attr == 2) {
            return 'Don\'t Know';
        }

        if ($this->$attr == 1) {
            return 'Yes';
        }

        return 'No';
    }
}
