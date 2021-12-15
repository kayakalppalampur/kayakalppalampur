<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsultationCharge extends Model
{

    const TYPE_BASIC_CHARGES = 0;
    const TYPE_DEPARTMENT_CHARGES = 1;
    const TYPE_DOCTOR_CHARGES = 1;

    protected $fillable = [
        'charges',
        'foreign_charges',
        'type',
        'status',
        'department_id',
        'doctor_id',
        'created_by',
    ];
    protected $table = 'consultation_charges';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public static function rules()
    {
        return [
            'charges' => 'required|numeric',
        ];
    }

    public static function getConsultFees($id = null)
    {
        $fees = ConsultationCharge::where('department_id', $id)->first();

        if ($fees != null) {
            return $fees->charges;
        }

        return Settings::BASIC_PRICE;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.consultation_charges',
        ];
    }

    public function setData($request)
    {
        $this->charges = $request->get('charges');
        $this->foreign_charges = $request->get('foreign_charges');
        $this->type = $request->get('type') ? $request->get('type') : self::TYPE_BASIC_CHARGES;
        $this->department_id = $request->get('department_id');
        $this->doctor_id = $request->get('doctor_id');
        $this->created_by = \Auth::user()->id;
        return $this;
    }
}
