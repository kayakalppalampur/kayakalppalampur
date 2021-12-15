<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DietChartItems extends Model
{

    const TYPE_BREAKFAST = 1;
    const TYPE_LUNCH = 2;
    const TYPE_POST_LUNCH = 3;
    const TYPE_DINNER = 4;
    const TYPE_SPECIAL = 5;

    const TYPE_BREAKFAST_TIME = "08:30:00";
    const TYPE_BREAKFAST_START_TIME = "08:00:00";

    const TYPE_LUNCH_TIME = "13:00:00";
    const TYPE_LUNCH_START_TIME = "12:00:00";

    const TYPE_POST_LUNCH_TIME = "16:30:00";
    const TYPE_POST_LUNCH_START_TIME = "16:00:00";

    const TYPE_DINNER_TIME = "19:30:00";
    const TYPE_DINNER_START_TIME = "18:30:00";
    const TYPE_SPECIAL_TIME = "20:30:00";
    const TYPE_SPECIAL_START_TIME = "20:00:00";

    protected $fillable = [
        'diet_id',
        'time',
        'item_id',
        'type_id',
        'created_by',
        'status',
        'item_price'
    ];

    protected $table = 'diet_chart_items';

    public function item()
    {
        return $this->belongsTo("App\KitchenItem", 'item_id');
    }

    public static function getRules()
    {
        return [
            'diet_id' => 'required',
            'item_id' => 'required',
            'type_id' => 'required',
        ];
    }

    public function setData($data)
    {
        $this->diet_id = $data->get("diet_id");
        $this->created_by = \Auth::user()->id;
        $this->item_id = $data->get("item_id");
        $this->type_id = $data->get("type_id");
        $this->time = $data->get("time") != null ? $data->get("time") : self::getTimeOptions($data->get("type_id"));

        if ($this->item_price == null) {
            $item = KitchenItem::find($this->item_id);
            if ($item) {
                $this->item_price = $item->price;
            }
        }
        return $this;
    }

    public static function getTimeOptions($id)
    {
        $list = [
            self::TYPE_BREAKFAST => self::TYPE_BREAKFAST_TIME,
            self::TYPE_DINNER => self::TYPE_DINNER_TIME,
            self::TYPE_LUNCH => self::TYPE_LUNCH_TIME,
            self::TYPE_POST_LUNCH => self::TYPE_POST_LUNCH_TIME,
            self::TYPE_SPECIAL => self::TYPE_SPECIAL_TIME
        ];

        return $list[$id];
    }

    public static function getStartTimeOptions($id)
    {
        $list = [
            self::TYPE_BREAKFAST => self::TYPE_BREAKFAST_START_TIME,
            self::TYPE_DINNER => self::TYPE_DINNER_START_TIME,
            self::TYPE_LUNCH => self::TYPE_LUNCH_START_TIME,
            self::TYPE_POST_LUNCH => self::TYPE_POST_LUNCH_START_TIME,
            self::TYPE_SPECIAL => self::TYPE_SPECIAL_START_TIME
        ];

        return $list[$id];
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            self::TYPE_BREAKFAST => "Breakfast (8:30AM)",
            self::TYPE_LUNCH => "Lunch (12:30)",
            self::TYPE_POST_LUNCH => "Post Lunch (4:00 PM)",
            self::TYPE_DINNER => "Dinner (7:00PM)",
            self::TYPE_SPECIAL => "Special (8:30Pm)"
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }

    public static function getItems($id, $type, $date)
    {
        $date = (string) $date;

        $diet = DietChart::where("start_date", "<=", $date)->where("end_date", ">=", $date)->where('patient_id', $id)->first();
        if ($diet != null) {
            $diet_items = DietChartItems::where("diet_id", $diet->id)->where('type_id', $type)->get();
            
            if (count($diet_items) > 0) {
                return $diet_items;
            }

        }
        return [];
    }


    public static function getTypeDropDownList()
    {
        $list = [];
        foreach (self::getTypeOptions() as $id => $type) {
            $list[] = [
                    'value' => $id,
                    'show' => $type,
                ];
        }
        return $list;
    }

    public static function getTimes($type)
    {
        return date("h:i a", strtotime(self::getStartTimeOptions($type))). ' to '.date("h:i a", strtotime(self::getTimeOptions($type))) ;
    }

    public function customDelete()
    {
        $this->delete();
    }
}
