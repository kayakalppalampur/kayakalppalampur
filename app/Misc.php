<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Misc extends Model
{
    //
    
    protected $fillable = [
        'created_by',
        'name',
        'price',
        'booking_id',
        'bill_id'
    ];

    protected $table = 'miscs';

    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }

    public static function getRules()
    {
        return [
            'name' => 'required',
            'booking_id' => 'required',
        ];
    }

    public function booking()
    {
        return $this->belongsTo("App\Booking", "booking_id");
    }

    public function setData($request)
    {
        $this->created_by = \Auth::user()->id;
        $this->name = $request->get('name');
        $this->price = $request->get('price') ? $request->get('price') : 0;
        $this->booking_id = $request->get('booking_id');
        return $this;
    }

    public function customDelete()
    {
        $this->delete();
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::miscs',
            'Laralum::miscs.print',
            'Laralum::miscs.export',
            'Laralum::miscs.create',
            'Laralum::miscs.edit',
            'Laralum::miscs.delete'
        ];
    }

    public static function getCategoryDropdownList()
    {
        $models = self::getCategoryOptions();
        $uid = [];
        foreach ($models as $k => $v) {
            $uid[] = [
                'value' => $k,
                'show' => $v,
            ];
        }

        return $uid;
    }


}
