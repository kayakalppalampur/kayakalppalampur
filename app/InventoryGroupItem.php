<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryGroupItem extends Model
{
    //
    protected $fillable = [
        'title', 'created_by', 'group_id', 'description'
    ];

    protected $table = 'inventory_group_items';

    public function group()
    {
        return $this->belongsTo('App\InventoryGroup', 'group_id');
    }

    public static function getRules()
    {
        return [
            'title' => 'required',
            'group_id' => 'required',
        ];
    }

    public function setData($request)
    {
        $this->title = $request->get("title");
        $this->group_id = $request->get("group_id");
        $this->description = $request->get('description');
        $this->created_by = \Auth::user()->id;
        return $this;
    }

    public function customDelete()
    {
        $this->delete();
    }

    public static function importData($arr, $group_id)
    {
        if(!empty($arr['description'])) {
            $model = new InventoryGroupItem();
            $model->title = $arr['description'];
            $model->group_id = $group_id;
            $model->description = $arr['titleitem'];
            $model->created_by = \Auth::user()->id;
            $model->save();
        }
    }
}
