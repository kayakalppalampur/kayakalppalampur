<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryGroup extends Model
{
    //
    protected $fillable = [
        'title', 'created_by', 'description'
    ];

    protected $table = 'inventory_groups';

    public static function getRules()
    {
        return [
            'title' => 'required'
        ];
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::groups',
            'Laralum::group.add',
            'Laralum::group.edit',
            'Laralum::group.delete'
        ];
    }

    public static function getItemRoutesArray()
    {
        return [
            'Laralum::group-items',
            'Laralum::group-item.add',
            'Laralum::group-item.edit',
            'Laralum::group-item.delete'
        ];
    }

    public static function getStockRoutesArray()
    {
        return [
            'Laralum::stock',
            'Laralum::stock.create',
            'Laralum::stock.edit',
            'Laralum::stock.delete',
        ];
    }

    public static function getStockRequestArray()
    {
        return [
            'Laralum::stock.item_requests',
            'Laralum::item_request.delete',
            'Laralum::stock.approve'
        ];
    }

    public function items()
    {
        return $this->hasMany('App\InventoryGroupItem', 'group_id');
    }

    public function setData($request)
    {
        $this->title = $request->get("title");
        $this->description = $request->get("description");
        $this->created_by = \Auth::user()->id;
        return $this;
    }

    public function customDelete()
    {
        foreach ($this->items as $item) {
            $item->customDelete();
        }
        $this->delete();
    }
}
