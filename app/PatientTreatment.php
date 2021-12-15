<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    const STATUS_DISCHARGED = 2;
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;

    protected $fillable = [
        'treatment_token_id',
        'patient_id',
        'treatment_id',
        'ratings',
        'status',
        'not_attended_reason',
        'reason_submitted_by',
        'booking_id',
        'price'
    ];

    protected $table = 'patient_treatments';

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function treatment()
    {
        return $this->belongsTo("App\Treatment", "treatment_id");
    }

    public function treatmentToken()
    {
        return $this->belongsTo("App\TreatmentToken", "treatment_token_id");
    }


    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_PENDING => 'NO',
            self::STATUS_COMPLETED => 'YES',
            self::STATUS_DISCHARGED => 'Discharged',
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }
}
