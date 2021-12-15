<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentPackage extends Model
{
    const TYPE_MINUTES = 0;
    const TYPE_HOURS = 1;

    protected $fillable = [
        'package_name',
        'duration',
        'price',
        'type',
        'status',
        'department_id',
        'created_by',
    ];
    protected $table = 'treatment_packages';

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public static function rules()
    {
        return [
            'package_name' => 'required',
            'duration' => 'required|numeric',
            'price' => 'required|numeric',
            'type' => 'required',
            'department_id' => 'required',
            'treatment_id' => 'required'
        ];
    }

    public function setData($request)
    {
        $this->package_name = $request->get('package_name');
        $this->duration = $request->get('duration');
        $this->price = $request->get('price');
        $this->department_id = $request->get('department_id');
        $this->type = $request->get('type');
        $this->created_by = \Auth::user()->id;
        return $this;
    }

    public function deleteOldTreatments()
    {
        $treatments = TreatmentPackageTreatments::where('package_id', $this->id)->get();
        foreach ($treatments as $treatment) {
            $treatment->delete();
        }
    }

    public function saveTreatments($ids)
    {
        $this->deleteOldTreatments();
        $ids = !is_array($ids) ? explode(',', $ids) : $ids;
        foreach ($ids as $id) {
            $treatment = TreatmentPackageTreatments::where('treatment_id', $id)->where('package_id', $this->id)->first();
            if ($treatment == null) {
                TreatmentPackageTreatments::create([
                    'treatment_id' => $id,
                    'package_id' => $this->id]);
            }
        }
    }

    public function getDuration()
    {
        if ($this->type == self::TYPE_MINUTES) {
            return $this->duration.' m';
        }
        return $this->duration.' h';
    }

    public function treatments()
    {
        return $this->hasMany("App\TreatmentPackageTreatments", 'package_id');
    }
    
    public function getTreatmentsList($ids = false)
    {
        $html = "";
        $selected_ids = [];
        foreach ($this->treatments as $treatment) {
            if ($ids == true) {
                $selected_ids[] = $treatment->treatment_id;
            }
            $html .= $treatment->treatment->title.'<br/>';
        }
        if ($ids == true) {
            return $selected_ids;
        }
        return $html;
    }
}
