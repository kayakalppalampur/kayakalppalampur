<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkinExamination extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;
    
    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
        'booking_id',
        'skin'
    ];

    protected $table = 'skin_examinations';

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->skin = $request->get('skin');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->update([
                'status' =>$status,
                'booking_id' => $b_id
            ]);
        }
    }

    
}
