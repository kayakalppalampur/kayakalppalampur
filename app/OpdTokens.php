<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpdTokens extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_ATTENDED = 1;
    const STATUS_EXPIRED = 2;
    const STATUS_CANCELLED = 3;

    protected $fillable = [
        'booking_id',
        'patient_id',
        'doctor_id',
        'department_id',
        'date',
        'complaints',
        'status',
        'reference_number',
        'first_name',
        'last_name',
        'mobile',
        'gender',
        'profession',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'charges'
    ];

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
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

    public function professionName()
    {
        return $this->belongsTo('App\Profession','profession');
    }

    public function setData($request)
    {
        $this->patient_id = $request->get('patient_id');
        $this->booking_id = $request->get('booking_id');
        $this->department_id = $request->get('department_id');
        $this->doctor_id = $request->get('doctor_id');
        $this->complaints = $request->get('complaints');
        $this->reference_number = $this->getNumber();
        $this->date = date('Y-m-d H:i:s');
        $this->first_name = $request->get('first_name');
        $this->last_name = $request->get('last_name');
        $this->mobile = $request->get('mobile');
        $this->gender = $request->get('gender');
        $this->profession = $request->get('profession');
        $this->dob = $request->get('dob');
        $this->address = $request->get('address');
        $this->city = $request->get('city');
        $this->state = $request->get('state');
        $this->country = $request->get('country');
        $this->status = PatientToken::STATUS_PENDING;
        return $this;
    }

    public function getAge()
    {
        $age = 'NA';
        if ($this->age != null) {
            return $this->age;
        }

        if (!empty($this->dob) && $this->dob != NUll) {
            $from = new \DateTime($this->dob);
            $to = new \DateTime('today');
            $age = $from->diff($to)->y;
        }
        return $age;
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
            'Laralum::opd-tokens',
        ];
    }

    public function getNumber()
    {
        $token = OpdTokens::where('date', (string)date('Y-m-d'))->orderBy('id', 'desc')->count();
        return (int)$token + 1;
    }
}
