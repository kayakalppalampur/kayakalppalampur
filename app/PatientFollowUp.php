<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientFollowUp extends Model
{
    //

    const STATUS_CANCELLD = 1;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'status',
        'followup_date'
    ];

    public function patient()
    {
        return $this->belongsTo('App\DischargePatient', 'patient_id');
    }

    public static function customDeleteBooking($b_id)
    {
        $models = self::where('patient_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function setData($request)
    {
        $this->patient_id  = $request->get('patient_id');
        $this->doctor_id  = $request->get('doctor_id');
        $this->followup_date  = $request->get('followup_date');
        return $this;
    }

}
