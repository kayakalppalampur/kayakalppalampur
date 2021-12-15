<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespiratoryExamination extends Model
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
        'cough',
        'cough_doctor',
        'fever',
        'fever_doctor',
        'sinusitis',
        'sinusitis_doctor',
        'chest_pain',
        'chest_pain_doctor',
        'wheeze',
        'wheeze_doctor',
        'hoarsness',
        'hoarsness_doctor',
        'doctor_details',
        'booking_id'
    ];

    protected $table = 'respiratory_examinations';

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
        $this->cough = $request->get('cough');
        $this->cough_doctor = $request->get('cough_doctor');
        $this->fever = $request->get('respiratory_fever');
        $this->fever_doctor = $request->get('respiratory_fever_doctor');
        $this->sinusitis = $request->get('sinusitis');
        $this->sinusitis_doctor = $request->get('sinusitis_doctor');
        $this->chest_pain = $request->get('chest_pain');
        $this->chest_pain_doctor = $request->get('chest_pain_doctor');
        $this->wheeze = $request->get('wheeze');
        $this->wheeze_doctor = $request->get('wheeze_doctor');
        $this->hoarsness = $request->get('hoarsness');
        $this->hoarsness_doctor = $request->get('hoarsness_doctor');
        $this->doctor_details = $request->get('respiratory_doctor_details');
        return $this;
    }

    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
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
