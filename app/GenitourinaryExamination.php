<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenitourinaryExamination extends Model
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
        'fever',
        'fever_doctor',
        'loin_pain',
        'loin_pain_doctor',
        'dysuria',
        'dysuria_doctor',
        'urethral_discharge',
        'urethral_discharge_doctor',
        'painful_sexual_intercourse',
        'painful_sexual_intercourse_doctor',
        'menarche',
        'menarche_doctor',
        'menopause',
        'menopause_doctor',
        'length_of_periods',
        'length_of_periods_doctor',
        'amount_pain',
        'amount_pain_doctor',
        'LMP',
        'LMP_doctor',
        'booking_id'
    ];

    protected $table = 'genitourinary_examinations';

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
        $this->fever = $request->get('fever');
        $this->fever_doctor = $request->get('fever_doctor');
        $this->loin_pain = $request->get('loin_pain');
        $this->loin_pain_doctor = $request->get('loin_pain_doctor');
        $this->dysuria = $request->get('dysuria');
        $this->dysuria_doctor = $request->get('dysuria_doctor');
        $this->urethral_discharge = $request->get('urethral_discharge');
        $this->urethral_discharge_doctor = $request->get('urethral_discharge_doctor');
        $this->painful_sexual_intercourse = $request->get('painful_sexual_intercourse');
        $this->painful_sexual_intercourse_doctor = $request->get('painful_sexual_intercourse_doctor');
        $this->menarche = $request->get('menarche');
        $this->menarche_doctor = $request->get('menarche_doctor');
        $this->menopause = $request->get('menopause');
        $this->menopause_doctor = $request->get('menopause_doctor');
        $this->length_of_periods = $request->get('length_of_periods');
        $this->length_of_periods_doctor = $request->get('length_of_periods_doctor');
        $this->amount_pain = $request->get('amount_pain');
        $this->amount_pain_doctor = $request->get('amount_pain_doctor');

        if ($request->LMP) {
            $this->LMP = date("Y-m-d", strtotime($request->get('LMP')));
        }else{
            $this->LMP = null;
        }

        $this->LMP_doctor = $request->get('LMP_doctor');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
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
