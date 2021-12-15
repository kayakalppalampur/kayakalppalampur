<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastrointestinalExamination extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
        'abdominal_pain',
        'abdominal_pain_doctor',
        'nausea',
        'nausea_doctor',
        'dysphagia',
        'dysphagia_doctor',
        'indigestion',
        'indigestion_doctor',
        'change_in_bowel_habits',
        'change_in_bowel_habits_doctor',
        'diarrhoea_constipation',
        'diarrhoea_constipation_doctor',
        'rectal_bleeding',
        'rectal_bleeding_doctor',
        'weight_change',
        'weight_change_doctor',
        'dark_urine',
        'dark_urine_doctor',
        'booking_id'
    ];

    protected $table = 'gastrointestinal_examinations';

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
        $this->patient_id = $request->get("patient_id");
        $this->abdominal_pain = $request->get('abdominal_pain');
        $this->abdominal_pain_doctor = $request->get('abdominal_pain_doctor');
        $this->nausea = $request->get('nausea');
        $this->nausea_doctor = $request->get('nausea_doctor');
        $this->dysphagia = $request->get('dysphagia');
        $this->dysphagia_doctor = $request->get('dysphagia_doctor');
        $this->indigestion = $request->get('indigestion');
        $this->indigestion_doctor = $request->get('indigestion_doctor');
        $this->change_in_bowel_habits = $request->get('change_in_bowel_habits');
        $this->change_in_bowel_habits_doctor = $request->get('change_in_bowel_habits_doctor');
        $this->diarrhoea_constipation = $request->get('diarrhoea_constipation');
        $this->diarrhoea_constipation_doctor = $request->get('diarrhoea_constipation_doctor');
        $this->rectal_bleeding = $request->get('rectal_bleeding');
        $this->rectal_bleeding_doctor = $request->get('rectal_bleeding_doctor');
        $this->weight_change = $request->get('weight_change');
        $this->weight_change_doctor = $request->get('weight_change_doctor');
        $this->dark_urine = $request->get('dark_urine');
        $this->dark_urine_doctor = $request->get('dark_urine_doctor');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('status', self::STATUS_PENDING)->get();
        foreach ($models as $model) {
            $model->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }


    public static function customDelete($id, $b_id)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function getValue($attr)
    {
        if ($this->$attr == 2) {
            return 'Don\'t Know';
        }

        if ($this->$attr == 1) {
            return 'Yes';
        }

        return 'No';
    }

}
