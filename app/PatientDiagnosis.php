<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientDiagnosis extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;
    
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'booking_id',
        'description',
        'others',
        'status',
        'type',
        'date'
    ];

    protected $table = "patient_diagnosis";

    public function rules()
    {
        return [
            'description' => 'required',
            'patient_id' => 'required',
            'booking_id' => 'required',
        ];
    }

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function setData($request)
    {
        $this->description = $request->get('description');
        $this->patient_id = $request->get('patient_id');
        $this->booking_id = $request->get('booking_id');
        $this->doctor_id = \Auth::user()->id;
        $this->date = date("Y-m-d");
        $this->status = self::STATUS_PENDING;
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $details = PatientDiagnosis::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $data) {
            $data->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $details = PatientDiagnosis::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $data) {
            $data->delete();
        }
    }

}
