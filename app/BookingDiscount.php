<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingDiscount extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'discount_id',
        'basic_amount',
        'discount_amount',
        'description',
        'status',
        'created_by',
        'bill_id'
    ];

    protected $table = 'booking_discounts';
    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }
    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }
    public static function customDeleteBooking($b_id)
    {
        $models = self::where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function createUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function discount()
    {
        return $this->belongsTo('App\DiscountOffer', 'discount_id');
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'booking_id' => 'required',
            'basic_amount' => 'required',
            'discount_amount' => 'required',
        ];
    }

}
