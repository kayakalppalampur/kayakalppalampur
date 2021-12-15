<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxDetail extends Model
{

    protected $fillable = [
        'tax_type',
        'tax_amount',
        'date',
        'status',
        'created_by',
    ];
    protected $table = 'tax_details';

    public static function rules()
    {
        return [
            'tax_type' => 'required',
            'tax_amount' => 'required|numeric|max:100',
            'date' => 'required',
        ];
    }

    public function setData($request)
    {
        $this->tax_type = $request->get('tax_type');
        $this->tax_amount = $request->get('tax_amount');
        $this->date = date('Y-m-d',strtotime($request->get('date')));
        $this->created_by = \Auth::user()->id;
        return $this;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.tax_details',
            'Laralum::admin.tax_details.search',
            'Laralum::admin.tax_details.search',
            'Laralum::admin.tax_details.add',
            'Laralum::admin.tax_details.edit',
            'Laralum::tax_details.delete'
        ];
    }
}
