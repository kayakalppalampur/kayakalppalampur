<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentPackageTreatments extends Model
{
    const TYPE_MINUTES = 0;
    const TYPE_HOURS = 1;

    protected $fillable = [
        'package_id',
        'treatment_id',
        'status',
        'created_by',
    ];
    protected $table = 'treatment_packages_treatments';

    public function rules()
    {
        return [
            'package_id' => 'required',
            'treatment_id' => 'required',
        ];
    }

    public function setData($request)
    {
        $this->package_id = $request->get('package_id');
        $this->treatment_id = $request->get('treatment_id');
        $this->created_by = \Auth::user()->id;
    }

    public function package()
    {
        return $this->belongsTo("App\TreatmentPackage", 'package_id');
    }

    public function treatment()
    {
        return $this->belongsTo("App\Treatment", 'treatment_id');
    }

}
