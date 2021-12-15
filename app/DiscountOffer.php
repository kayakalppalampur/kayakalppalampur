<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountOffer extends Model
{
    //

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const TYPE_PERC = 0;
    const TYPE_FLAT = 1;

    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'status',
        'expiry_date',
        'status',
    ];

    public static function getRules($id = null)
    {
        return [
            'code' => 'required',
            'discount_value' => 'required|numeric'
        ];
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::TYPE_FLAT => 'Flat',
            self::TYPE_PERC => "Percentage"
        ];
        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public static function getTypeOptionsDropdwon()
    {
        $list = [
            [
                'value' => self::TYPE_FLAT,
                'show' => 'Flat'
            ],
            [
                'value' => self::TYPE_PERC,
                'show' => 'Percentage'
            ],
        ];

        return $list;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::discount_offers',
            'Laralum::discount_offer_create',
            'Laralum::discount_offer_edit',
            'Laralum::discount_offer.view',
            'Laralum::discount_offer_delete'
        ];
    }
    
    public function setData($data)
    {
        $this->code = $data->get('code');
        $this->type = $data->get('type');
        $this->discount_value = $data->get('discount_value');
        $this->status = $data->get('status');
        $this->expiry_date = date("Y-m-d", strtotime($data->get('expiry_date')));
        $this->status = $data->get('status') != null ? $data->get('status') : self::STATUS_ACTIVE;
        return $this;
    }
}
