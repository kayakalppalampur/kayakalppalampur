<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{

    protected $fillable = [
        'name', 'created_by'
    ];

    protected $table = 'stock_categories';


    public static function getRules()
    {
        return [
            'name' => 'required',
        ];
    }

    public function setData($data)
    {
        $this->name = $data->get("name");
        $this->created_by = \Auth::user()->id;
        return $this;
    }
}
