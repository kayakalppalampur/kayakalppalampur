<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientDetails extends Model
{
    //
    const TYPE_ADMISSION = 0;
    const TYPE_DISCHARGE = 1;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    const TYPE_YES = 1;
    const TYPE_NO = 0;
    const TYPE_DONTKNOW = 2;

    protected $fillable = [
        'patient_id',
        'pulse',
        'bp',
        'height',
        'weight',
        'blood_group',
        'bmi',
        'created_by',
        'token_id',
        'type',
        'status',
        'booking_id'
    ];

    public function checkRequest($request)
    {
        if ($request->get("bp") != null || $request->get("pulse") != null || $request->get("weight") != null)
            return true;

        return false;
    }

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function createUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function setData($request)
    {
        //return $request->get('status');
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get('patient_id');
        $this->token_id = $request->get('token_id');
        $this->pulse = $request->get('pulse');
        $this->bp = $request->get('bp');
        $this->created_by = \Auth::user()->id;
        $this->height = $request->get('height') ? $request->get('height') : $this->getLast('height');
        $this->weight = $request->get('weight');
        $this->bmi = $this->calculateBmi();
        $this->status = self::STATUS_PENDING;
       // $this->status = $request->get('status') ? $request->get('status') : self::STATUS_PENDING;
        $this->type = $request->get('type') ? $request->get('type') : self::TYPE_ADMISSION;
        $this->blood_group = $request->get('blood_group') ? $request->get('blood_group') : $this->getLast('blood_group');
        return $this;
    }

    public function getLast($attr = 'height')
    {
        $prev_detail = PatientDetails::where('patient_id', $this->patient_id)->orderBy('created_at', 'DESC')->first();
        if ($prev_detail != null) {
            return $prev_detail->$attr;
        }

        return "";
    }

    public function calculateBmi()
    {
        $height = (float)$this->height;
        $weight = (float)$this->weight;

        if ($height > 0 && $weight > 0) {
            $bmi = $weight / ($height * $height);
            $bmi = $bmi * 10000;
            return round($bmi, 2);
        }
        return 0;
    }

    public static function getAllRelations()
    {
        return [
            'patient',
            'patient.userProfile',
        ];
    }

    public static function getDetailsId($id)
    {
        $details = PatientDetails::where("patient_id", $id)->orderBy('created_at', "DESC")->first();
        if ($details != null) {
            return $details->id;
        }
        return 0;
    }

    public static function discharge($id, $b_id, $status)
    {
        $details = PatientLabTest::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $detail) {
            $detail->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $details = PatientLabTest::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::patients',
            'Laralum::patient.show',
            'Laralum::patient.diagnosis',
            'Laralum::patient.vital_data',
            'Laralum::patient.ayurvedic_vital_data',
            'Laralum::patient.treatment_history',
            'Laralum::patient.treatment',
            'Laralum::patient.print_treatment',
            'Laralum::patient.print_treatment',
            'Laralum::patient.treatment_edit',
            'Laralum::treatment_token.delete',
            'Laralum::patient.ayurved_vital_data',
            'Laralum::patient_lab_test.index',
            'Laralum::patient_lab_test.add',
            'Laralum::patient_lab_test.add',
            'Laralum::patient_lab_test.delete',
            'Laralum::discharge.patient',
            'Laralum::treatment_tokens',
            'Laralum::patient.diet-chart',
            'Laralum::add-patient-diet-chart-details',
            'Laralum::patient.edit-diet-chart'
        ];
    }
}
