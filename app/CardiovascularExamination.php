<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardiovascularExamination extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;
    
    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
        'chest_pain',
        'chest_pain_doctor',
        'dyspnoea',
        'dyspnoea_doctor',
        'palpitations',
        'palpitations_doctor',
        'dizziness',
        'dizziness_doctor',
        'doctor_details',
        'booking_id'
    ];

    protected $table = 'cardiovascular_examinations';

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
        $this->chest_pain = $request->get('cardio_chest_pain');
        $this->chest_pain_doctor = $request->get('cardio_chest_pain_doctor');
        $this->dyspnoea = $request->get('dyspnoea');
        $this->dyspnoea_doctor = $request->get('dyspnoea_doctor');
        $this->palpitations = $request->get('palpitations');
        $this->palpitations_doctor = $request->get('palpitations_doctor');
        $this->dizziness = $request->get('dizziness');
        $this->dizziness_doctor = $request->get('dizziness_doctor');
        $this->doctor_details = $request->get('cardio_doctor_details');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->update([
                'status' => self::STATUS_DISCHARGED,
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
