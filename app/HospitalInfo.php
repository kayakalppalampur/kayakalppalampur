<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalInfo extends Model
{

    protected $fillable = [
        'hospital_name',
        'address',
        'city',
        'state',
        'pincode',
        'phone_no',
        'mobile_no',
        'fax',
        'email',
        'website',
        'created_by',
        'status'
    ];
    protected $table = 'hospital_info';

    public static function rules()
    {
        return [
            'hospital_name' => 'required',
            'phone_no' => 'integer|digits:10',
            'mobile_no' => 'integer|digits:10',
            'email' => 'email',
            'website' => 'url',
        ];
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.hospital_info',
        ];
    }

    public function setData($request)
    {
        $this->hospital_name = $request->get('hospital_name');
        $this->address = $request->get('address');
        $this->city = $request->get('city');
        $this->state = $request->get('state');
        $this->pincode = $request->get('pincode');
        $this->phone_no = $request->get('phone_no');
        $this->mobile_no = $request->get('mobile_no');
        $this->fax = $request->get('fax');
        $this->email = $request->get('email');
        $this->website = $request->get('website');
        $this->created_by = \Auth::user()->id;
        return $this;
    }
}
