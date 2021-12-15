<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VitalData extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'patient_id',
        'token_no',
        'present_complaints',
        'doctor_id',
        'present_illness',
        'treatment_details',
        'past_illness',
        'past_investigation',
        'status',
        'booking_id',
        'family_history',
        'gynecological_obs_history',
        'personal_history',
        'diet',
        'sleep',
        'appetite',
        'bowel',
        'exercise',
        'digestion',
        'habits',
        'urine',
        'addiction',
        'tongue',
        'water_intake',
    ];
    protected $table = 'vital_data';

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function token()
    {
        return $this->belongsTo('App\PatientToken', 'token_no');
    }

    public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get('patient_id');
        $this->token_id = $request->get('token_id');
        $this->doctor_id = \Auth::user()->id;
        $this->present_complaints = $request->get('present_complaints');
        $this->past_illness = $request->get('past_illness');
        $this->present_illness = $request->get('present_illness');
        $this->treatment_details = $request->get('treatment_details');
        $this->past_investigation = $request->get('past_investigation');
        $this->family_history = $request->get('family_history');
        $this->gynecological_obs_history = $request->get('gynecological_obs_history');
        $this->personal_history = $request->get('personal_history');
        $this->diet = $request->get('diet');
        $this->sleep = $request->get('sleep');
        $this->appetite = $request->get('appetite');
        $this->bowel = $request->get('bowel');
        $this->exercise = $request->get('exercise');
        $this->digestion = $request->get('digestion');
        $this->habits = $request->get('habits');
        $this->urine = $request->get('urine');
        $this->addiction = $request->get('addiction');
        $this->tongue = $request->get('tongue');
        $this->water_intake = $request->get('water_intake');
        $this->status = self::STATUS_COMPLETED;
        return $this;
    }

    public static function getAllRelations()
    {
        return [
            'patient',
            'patient.userProfile',
        ];
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function setComplaints()
    {
        $booking = $this->booking;
        if ($booking != null) {
            $healthIssues = isset($booking->healthIssues->health_issues) ? $booking->healthIssues->health_issues != null ? $booking->healthIssues->health_issues : $booking->user->userProfile->health_issues : "";
            $this->present_complaints = $healthIssues;
            $this->present_illness = $healthIssues;
        }

        return $this;
    }

    public static function discharge($id, $b_id, $status)
    {
        $details = VitalData::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $data) {
            $data->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }


    public static function customDelete($id, $b_id)
    {
        $details = VitalData::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($details as $data) {
            $data->delete();
        }
    }

}
