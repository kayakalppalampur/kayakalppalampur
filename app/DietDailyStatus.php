<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DietDailyStatus extends Model
{
    //
    const TYPE_BREAKFAST = 1;
    const TYPE_LUNCH = 2;
    const TYPE_POST_LUNCH = 3;
    const TYPE_DINNER = 4;
    const TYPE_SPECIAL = 5;

    const STATUS_DONE = 1;
    const STATUS_PENDING = 0;

    protected $fillable = [
        'diet_id',
        'date',
        'is_breakfast',
        'is_lunch',
        'is_post_lunch',
        'is_dinner',
        'is_special',
        'status',
        'created_by'
    ];

    protected $table = 'diet_daily_status';

    protected $appends = [
        'date_date',
    ];

    public function getDateDateAttribute()
    {
        return date("d-m-Y", strtotime($this->date));
    }

    public function diet()
    {
        return $this->belongsTo("App\DietChart", "diet_id");
    }

    public static function getRules()
    {
        return [
            'diet_id' => 'required',
            'item_id' => 'required',
        ];
    }

    public function setData($data)
    {
        $this->diet_id = $data->get("diet_id");
        $this->created_by = \Auth::user()->id;
        $this->date = $data->get("date");
        $this->status = self::STATUS_DONE;
        return $this;
    }

    public static function getTypeOptions($id)
    {
        $list = [
            self::TYPE_BREAKFAST => "Breakfast",
            self::TYPE_DINNER => "Dinner",
            self::TYPE_LUNCH => "Lunch",
            self::TYPE_POST_LUNCH => "Post Lunch",
            self::TYPE_SPECIAL => "Special"
        ];

        return $list[$id];
    }

    public function getTotalAmount()
    {
        $total = 0;
        $breakfast = 0;
        if ($this->is_breakfast != 0) {
            $breakfast_ar = explode('-', $this->is_breakfast);
            if (isset($breakfast_ar[1]))
                $breakfast =  $breakfast_ar[1];
        }

        $dinner = 0;
        if ($this->is_dinner != 0) {
            $dinner_ar = explode('-', $this->is_dinner);
            if (isset($dinner_ar[1]))
                $dinner =  $dinner_ar[1];
        }

        $lunch = 0;
        if ($this->is_lunch != 0) {
            $lunch_ar = explode('-', $this->is_lunch);
            if (isset($lunch_ar[1]))
                $lunch =  $lunch_ar[1];
        }

        $post_lunch = 0;
        if ($this->is_post_lunch != 0) {
            $post_lunch_ar = explode('-', $this->is_post_lunch);
            if (isset($post_lunch_ar[1]))
                $post_lunch =  $post_lunch_ar[1];
        }

        $special = 0;
        if ($this->is_special != 0) {
            $special_ar = explode('-', $this->is_special);
            if (isset($special_ar[1]))
                $special =  $special_ar[1];
        }

        $total = (int) $breakfast + (int) $lunch + (int) $post_lunch + (int) $dinner + (int) $special;
        return $total;
    }

    public function checkType($type)
    {
        if ($type == DietChartItems::TYPE_BREAKFAST && $this->is_breakfast != 0) {
            return true;
        }
        if ($type == DietChartItems::TYPE_LUNCH && $this->is_lunch != 0) {
            return true;
        }
        if ($type == DietChartItems::TYPE_POST_LUNCH && $this->is_post_lunch != 0) {
            return true;
        }
        if ($type == DietChartItems::TYPE_DINNER && $this->is_dinner != 0) {
            return true;
        }
        if ($type == DietChartItems::TYPE_SPECIAL && $this->is_special != 0) {
            return true;
        }
        return false;
    }
}


