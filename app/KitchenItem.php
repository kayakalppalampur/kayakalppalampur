<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KitchenItem extends Model
{
    //
    protected $fillable = [
        'name',/* 'quantity', 'price', */
        'created_by', 'type', 'price'
    ];

    protected $table = 'kitchen_items';

    public static function getRules()
    {
        return [
            'name' => 'required',/*
            'quantity' => 'required',*/
            'price' => 'required',
            'type' => 'required',
            'ingredients' => 'required'
        ];
    }

    public function dietItems()
    {
        return $this->hasMany('App\DietChartItems', 'item_id');
    }

    public static function getTypeOptions($id = null)
    {
        $list = [
            DietChartItems::TYPE_BREAKFAST => "Breakfast (8:30AM)",
            DietChartItems::TYPE_DINNER => "Dinner (7:00PM)",
            DietChartItems::TYPE_LUNCH => "Lunch (12:30)",
            DietChartItems::TYPE_POST_LUNCH => "Post Lunch (4:00 PM)",
            DietChartItems::TYPE_SPECIAL => "Special (8:30Pm)"
        ];

        if (isset($list[$id]))
            return $list[$id];

        return $list;
    }

    public static function getDropDownList()
    {
        $items = self::all();

        foreach ($items as $item) {
            $uid[] = [
                'value' => $item->id,
                'show' => $item->name,
            ];

        }
        $uid[] = [
            'value' => 0,
            'show' => "Others",
        ];

        return $uid;
    }

    public static function getItems($type, $limit = null)
    {
        $items = KitchenItem::where('type', $type);
        if ($limit != null)
            $items = $items->limit($limit);
        $items = $items->get();

        if ($items->count() > 0) {
            return $items;
        }
        return [];
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::kitchen-items',
            'Laralum::kitchen-items.print',
            'Laralum::kitchen-item.export',
            'Laralum::kitchen-item.create',
            'Laralum::kitchen-item.edit',
            'Laralum::kitchen-item.delete'
        ];
    }

    public static function getRequirementsRoutesArray()
    {
        return [
            'Laralum::kitchen-item.requirements',
            'Laralum::kitchen-item.requirements.print',
            'Laralum::kitchen-item.exportRequirements',
            'Laralum::kitchen.selected_item.request',
            'Laralum::kitchen-item.request',
            'Laralum::kitchen-item.stock.items'
        ];
    }

    public static function getDietRoutesArray()
    {
        return [
            'Laralum::diet-chart',
            'Laralum::kitchen-patient.diet-chart'
        ];
    }

    public static function getMealRoutesArray()
    {
        return [
            'Laralum::meal-status',
            'Laralum::print-meal-status'
        ];
    }

    public static function getMealServingRoutesArray()
    {
        return [
            'Laralum::meal-servings',
        ];
    }

    public function setData($data)
    {
        $this->name = $data->get("name");
        $this->created_by = \Auth::user()->id;
        $this->price = $data->get("price");
        $this->type = $data->get("type");
        $this->quantity = $data->get("quantity") != null ? $data->get("quantity") : 0;
        return $this;
    }

    public function stockItems()
    {
        return $this->hasMany("App\Stock", 'product_id');
    }

    public function getRequiredItems($date)
    {
        /*$date = (string)date("Y-m-d", strtotime("+" . $i . " days"));*/

        $date = (string)date("Y-m-d", strtotime($date));

        $diet_chart_items = DietChartItems::join("diet_chart", "diet_chart.id", "=", "diet_chart_items.diet_id")->where("diet_chart_items.item_id", $this->id)->where("diet_chart.start_date", "<=", $date)->where("diet_chart.end_date", ">=", $date)->get();
        $given = 0;
        foreach ($diet_chart_items as $diet_chart_item) {
            $daily_item = DietDailyStatus::select("diet_daily_status.*")->where("date", $date)->where("diet_id", $diet_chart_item->diet_id)->first();
            if ($daily_item != null) {
                if ($diet_chart_item->type_id == DietChartItems::TYPE_BREAKFAST) {
                    if ($daily_item->is_breakfast != 0) {
                        $given++;
                    }
                }
                if ($diet_chart_item->type_id == DietChartItems::TYPE_LUNCH) {
                    if ($daily_item->is_lunch != 0) {
                        $given++;
                    }
                }
                if ($diet_chart_item->type_id == DietChartItems::TYPE_POST_LUNCH) {
                    if ($daily_item->is_post_lunch != 0) {
                        $given++;
                    }
                }
                if ($diet_chart_item->type_id == DietChartItems::TYPE_DINNER) {
                    if ($daily_item->is_dinner != 0) {
                        $given++;
                    }
                }
                if ($diet_chart_item->type_id == DietChartItems::TYPE_SPECIAL) {
                    if ($daily_item->is_special != 0) {
                        $given++;
                    }
                }
            }
        }
        return count($diet_chart_items) - $given;
    }

    public function getStockItemList()
    {
        $stock_items = Stock::where("product_id", $this->id)->get();
        $data = [];
        foreach ($stock_items as $stock_item) {
            $data[] = [
                'id' => $stock_item->id,
                'name' => $stock_item->name,
                'lastRequested' => $stock_item->lastRequested() ? $stock_item->lastRequested() : ""
            ];
        }
        return $data;
    }

    public function getStockItemsList()
    {
        $stock_items = Stock::where("product_id", $this->id)->get();
        $data = [];
        foreach ($stock_items as $stock_item) {
            $data[] = $stock_item->name;
        }

        return implode(',',$data);
    }

    public function saveItems($items)
    {
        $items = !is_array($items) ? array($items) : $items;
        $stock_ar = [];
        foreach ($items as $item) {
            if($item != ''){
                $stock_item = Stock::where([
                    "product_id" => $this->id,
                    "product_type" => 'kitchen-item',
                    'name' => $item
                ])->first();
                if ($stock_item == null) {
                    $stock_item = new Stock();
                }
                $stock_item->product_id = $this->id;
                $stock_item->product_type = 'kitchen-item';
                $stock_item->name = $item;
                $stock_item->save();
                $stock_ar[] = $stock_item->id;
            }
            
        }

        $stock_items = Stock::where("product_id", $this->id)->where('product_type', 'kitchen-item')->whereNotIn('id', $stock_ar)->get();
        $data = [];
        foreach ($stock_items as $stock_item) {
            $stock_item->customDelete();
        }
    }

    public function customDelete()
    {
        $stockItems = $this->stockItems;
        foreach ($stockItems as $stockItem) {
            $stockItem->customDelete();
        }

        $dietItems = $this->dietItems;
        foreach ($dietItems as $dietItem) {
            $dietItem->customDelete();
        }
        $this->delete();
    }
}
