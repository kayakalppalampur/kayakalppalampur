<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    
    protected $fillable = [
        'name', 'quantity', /*'price',*/ 'product_id','created_by', 'product_type',
        'quantity_units', 'alert_quantity'
    ];

    protected $appends = [
        'current_quantity'
    ];

    public function getCurrentQuantityAttribute()
    {
        $id = $this->id;
        $log = ItemQuantityLog::getCurrent($id);
        return $log;
    }

    protected $table = 'stock';

    public function product()
    {
        return $this->belongsTo("App\KitchenItem", "product_id");
    }

    public function itemRequests()
    {
        return $this->hasMany("App\StockItemRequest", "item_id");
    }

    public static function getRules()
    {
        return [
            'name' => 'required',
            /*'quantity' => 'required',*/
            'product_id' => 'required'
            /* 'price' => 'required'*/
        ];
    }

    public function setData($data, $add = false)
    {
        if($data->get("name") != ''){
            $product_ids = is_array($data->get("product_id")) ? implode(',', $data->get("product_id")) : $data->get("product_id");

            if ($add == true) {
                $product_ids = $this->product_id.','.$product_ids;
            }

            $this->name = $data->get("name");
            $this->product_id = $product_ids;
            $this->product_type = $data->get("product_type");
            $this->created_by = \Auth::user()->id;
            if ($this->id == null) {
                $this->quantity = $data->get("quantity") != null ? $data->get("quantity") : 0;
            }

            $this->quantity_units = $data->get('quantity_units');
            $this->alert_quantity = $data->get('alert_quantity');
            return $this;
        }
       
    }

    public function getProductsTypes()
    {
        $ids = explode(',', $this->product_id);

        if ($this->product_type == 'kitchen-item') {
            $products = KitchenItem::whereIn('id', $ids)->pluck('type', 'id')->toArray();
        }else{
            $products = InventoryGroupItem::whereIn('id', $ids)->pluck('group_id', 'id')->toArray();
        }

        return $products;
    }

    public function group()
    {
        return $this->belongsTo(InventoryGroup::class, 'product_type');
    }

    public function getGroup()
    {
        if (isset($this->group->title)) {
            return $this->group->title;
        }

        return $this->product_type;
    }
    

    public function getProducts()
    {
        $ids = explode(',', $this->product_id);

        if ($this->product_type == 'kitchen-item') {
            $products = KitchenItem::whereIn('id', $ids)->pluck('name', 'id')->toArray();
        }else{
            $products = InventoryGroupItem::whereIn('id', $ids)->pluck('title', 'id')->toArray();
        }

        return implode(',', $products);
    }

    public function lastRequested()
    {
        $request = StockItemRequest::where([
                                    'item_id' => $this->id,
                                    'created_by' => \Auth::user()->id,
                                    'status' => StockItemRequest::STATUS_PENDING
            ])->orderBy('created_at', "DESC")->first();
        if ($request != null) {
            return $request->quantity;
        }
        return null;
    }

    public function customDelete()
    {
        $item_requests = $this->itemRequests;
        foreach ($item_requests as $request) {
            $request->delete();
        }
        $this->delete();
    }
}
