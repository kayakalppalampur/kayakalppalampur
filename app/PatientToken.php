<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientToken extends Model
{
    //

    const STATUS_PENDING = 0;
    const STATUS_ATTENDED = 1;
    const STATUS_EXPIRED = 2;
    const STATUS_CANCELLED = 3;

    protected $fillable = [
        'booking_id',
        'patient_id',
        'token_no',
        'department_id',
        'doctor_id',
        'status',
        'start_date',
        'end_date',
    ];


    public static function customDeleteBooking($b_id)
    {
        $models = self::where('patient_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }
    public function patientDetails()
    {
        return $this->hasOne('App\PatientDetails', 'token_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public function vitalData()
    {
        return $this->hasOne('App\VitalData', 'token_id');
    }

    public function setData($request)
    {
        $this->patient_id = $request->get('patient_id');
        $this->department_id = $request->get('department_id');
        $this->doctor_id = $request->get('doctor_id');
        $this->status = $request->get('status');
        $this->start_date = date('Y-m-d H:i:s');
        $this->end_date = date('Y-m-d 23:59:59');
        $this->status = PatientToken::STATUS_PENDING;
        $this->token_no =  $this->setTokenNo();
        return $this;
    }
    
    public function setTokenNo()
    {
        $date = (string)date("Y-m-d");
        $last_token =  PatientToken::where([
            'department_id' => $this->department_id,
            'doctor_id' => $this->doctor_id,
        ])->where(\DB::raw('date(`start_date`)'), $date)->orderBy('created_at', 'DESC')->first();

        if ($last_token != null)
            return $last_token->token_no + 1;
        return 1;
    }

    public static function getAllRelations()
    {
        return [
            'patient',
            'patient.userProfile',
        ];
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
                self::STATUS_PENDING => 'New',
                self::STATUS_ATTENDED => 'Attended',
                self::STATUS_EXPIRED => 'Expired',
                self::STATUS_CANCELLED => 'Canclled',
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::tokens',
        ];
    }
}

