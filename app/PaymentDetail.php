<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    //
    const PAYMENT_METHOD_CREDIT = 0;
    const PAYMENT_METHOD_DEBIT = 1;
    const PAYMENT_METHOD_NET_BANKING = 2;
    const PAYMENT_METHOD_MOBILE_PAYMENTS = 3;
    const PAYMENT_METHOD_WALLET = 4;

    protected $fillable = [
        'booking_id',
        'status',
        'type',
        'user_id'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'user_id');
    }
    
    public static function customDeleteBooking($b_id)
    {
        $models = self::where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }
    
    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }


    public function setData($request)
    {
        $this->user_id = $request->get('user_id');
        $this->booking_id = $request->get('booking_id');
        $this->type = $request->get('type');
        return $this;
    }

    public static function getAllRelations()
    {
        return [
            'user',
            'booking',
        ];
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::PAYMENT_METHOD_CREDIT => 'Credit',
            self::PAYMENT_METHOD_DEBIT => 'Debit',
            self::PAYMENT_METHOD_NET_BANKING => 'Net Banking',
            self::PAYMENT_METHOD_MOBILE_PAYMENTS => 'Mobile Payments',
            self::PAYMENT_METHOD_WALLET => 'Cash',

        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }
}
