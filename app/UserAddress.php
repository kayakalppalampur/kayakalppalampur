<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'user_id',
        'address1',
        'address2',
        'city',
        'zip',
        'country',
        'referral_source',
        'state',
        'booking_id',
        'profile_id'
    ];

    protected $table = 'user_addresses';

    public function user() {
        return $this->belongsTo('App\User','user_id');
    }

    public function setData($data, $id = null) {

        $data = !is_object($data) ? (object) $data : $data;

        $data = new Collection($data);

        if(\Auth::check()) {
            if($id == null) {
                $id = \Auth::user()->id;
            }
        }
        $this->user_id          =   $id;
        $this->address1         =   $data->get('address1');
        $this->address2         =   $data->get('address2');
        $this->city             =   $data->get('city');
        $this->state             =   $data->get('state');
        $this->zip              =   $data->get('zip');
        $this->country          =   $data->get('country');
        $this->referral_source  =   $data->get('referral_source');

        return $this;
    }

    public static function getErrorMessages() {
        $messages   =   [
            'userAddress.address1.required'         =>  'Address 1 field is required',
            'userAddress.city.required'             =>  'City field is required',
            'userAddress.zip.required'              =>  'Zip field is required',
            'userAddress.country.required'          =>  'Country field is required',
            'userAddress.country.state'             =>  'State field is required',
            'userAddress.referral_source.required'  =>  'Referral Source field is required',
        ];

        return $messages;
    }

    public static function getRules() {
        $rules = [
            'userAddress.address1'          =>  'required',
            'userAddress.city'              =>  'required',
            'userAddress.country'           =>  'required',
            'userAddress.state'             =>  'required',
        ];

        return $rules;
    }

    public function customDelete()
    {
       $this->delete();
    }
}
