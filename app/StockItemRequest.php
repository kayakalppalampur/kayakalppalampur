<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockItemRequest extends Model
{
    //

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    protected $fillable = [
        'item_id', 'quantity', /*'price', 'product_id,'*/'created_by', 'status', 'approved_date', 'approved_qty'
    ];

    protected $appends = [
        'item_qty'
    ];

    protected $table = 'stock_item_requests';

    public function item()
    {
        return $this->belongsTo("App\Stock", "item_id");
    }

    public function getItemQtyAttribute()
    {
        $date = $this->approved_date;
        if ($date == null) {
            $date = date("Y-m-d");
        }
        $added = ItemQuantityLog::where('item_id', $this->item_id)->where('action', ItemQuantityLog::ACTION_ADDED)->where('created_at', '<', $date)->sum('qty');
        $removed = ItemQuantityLog::where('item_id', $this->item_id)->where('action', ItemQuantityLog::ACTION_REMOVED)->where('created_at', '<', $date)->sum('qty');

        return $added - $removed;
    }

    public function createUser()
    {
        return $this->belongsTo("App\User", "created_by");
    }

    public static function getRules()
    {
        return [
            'item_id' => 'required',
            'quantity' => 'required',
            /* 'price' => 'required'*/
        ];
    }

    public function setData($data)
    {
        $this->item_id = $data->get("item_id");
        $this->created_by = \Auth::user()->id;
        $this->quantity = $data->get("quantity") != null ? $data->get("quantity") : 0;
        return $this;
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_PENDING  => 'Pending',
            self::STATUS_APPROVED => 'Approved'
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }
}
