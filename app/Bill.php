<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'bill_no', 'bill_date', 'booking_id', 'amount_paid'
    ];
    
    protected $table = 'patient_bills';

    public static function getRoutesArray()
    {
        return [
            'Laralum::bills.create',
            'Laralum::bills',
            'Laralum::bills.edit',
            'Laralum::bills.delete'
        ];
    }

    public function customDelete()
    {
        $this->delete();
    }
}
