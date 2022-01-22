<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreatmentToken extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    const IS_SPECIAL = 1;
    const IS_NOT_SPECIAL = 0;

    protected $fillable = [
        'token_no',
        'patient_id',
        'treatment_date',

        'expiry_date',
        'patient_detail_id',
        'department_id',
        'feedback',
        'doctor_remark',
        'created_by',
        'note',
        'bp',
        'pulse',
        'weight',
        'status',
        'is_special',
        'booking_id',
        'bill_id'
    ];

    protected $table = 'treatment_tokens';
    
    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }

    protected $appends = [
        'treatment_date_date',
    ];

    public function getTreatmentDateDateAttribute()
    {
        return date("d-m-Y", strtotime($this->treatment_date));
    }


    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function patientDetail()
    {
        return $this->belongsTo("App\PatientDetails", "patient_detail_id");
    }

    public function department()
    {
        return $this->belongsTo("App\Department", "department_id");
    }

    public function departmentname()
    {
        $id = $this->department_id;
        $Department = Department::where("id", $id)->first();
        return $Department->title;
    }

    public function createUser()
    {
        return $this->belongsTo("App\User", "created_by");
    }

    public function treatments()
    {
        return $this->hasMany("App\PatientTreatment", "treatment_token_id");
    }

    public static function getRules()
    {
        return [
            /*'note' => 'required',*/
            /*'quantity' => 'required',*/
            'treatment_date' => 'required'
            /* 'price' => 'required'*/
        ];
    }

    public function setData($data)
    {
        $this->booking_id = $data->get("booking_id");
        $this->patient_id = $data->get("patient_id");
        $this->treatment_date = date('Y-m-d', strtotime($data->get("treatment_date")));
        $this->token_no = $this->getTokenNumber();

        $this->expiry_date = date('Y-m-d', strtotime($data->get("treatment_date")));
        $this->patient_detail_id = PatientDetails::getDetailsId($data->get("patient_id"));
        $this->department_id = \Auth::user()->department->department_id;
        $this->created_by = \Auth::user()->id;
        $this->note = $data->get("note");
        $this->bp = $data->get("bp");
        $this->pulse = $data->get("pulse");
        $this->weight = $data->get("weight");
        $this->is_special = $data->get("is_special") ? self::IS_SPECIAL : self::IS_NOT_SPECIAL;
        $this->status = self::STATUS_PENDING;
        return $this;
    }

    public function getTokenNumber()
    {
        if ($this->id == null) {
            $token = TreatmentToken::whereDate("treatment_date", $this->treatment_date)->orderBy("created_at", "DESC")->first();
            if ($token != null)
                return $token->token_no + 1;

            return 1;
        }

        return $this->token_no;
    }

    public function saveTreatments($data)
    {
        $this->deleteOldTreatments();
        $ids = $data->get("ids");

        foreach ($ids as $id) {
            $patient_treatment_model = Treatment::find($id);

            if ($patient_treatment_model) {
                $patient_treatment = PatientTreatment::where([
                    'treatment_id' => $id,
                    'treatment_token_id' => $this->id,
                    'patient_id' => $this->patient_id,
                    'price' => $patient_treatment_model->price
                ])->first();
                if ($patient_treatment == null && $id != null) {
                    PatientTreatment::create([
                        'treatment_id' => $id,
                        'treatment_token_id' => $this->id,
                        'patient_id' => $this->patient_id,
                        'status' => PatientTreatment::STATUS_COMPLETED,
                        'price' => $patient_treatment_model->price
                    ]);
                }
            }
        }
    }

    public function deleteOldTreatments()
    {
        $treatments = PatientTreatment::where('treatment_token_id', $this->id)->get();
        if ($treatments->count() > 0) {
            foreach ($treatments as $treatment) {
                $treatment->delete();
            }
        }
    }

    public function getTotalDuration()
    {
        $ids = [];
        $treatments = $this->treatments;
        if ($treatments != null) {
            foreach ($treatments as $treatment) {
                $ids[] = $treatment->treatment_id;
            }
        }

        return Treatment::getTotalDuration($ids);
    }

    public function deleteToken()
    {
        $patient_treatments = $this->treatments;

        if ($patient_treatments->count() > 0) {
            foreach ($patient_treatments as $patient_treatment) {
                $patient_treatment->delete();
            }
        }
        $this->delete();
    }


    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DISCHARGED => 'Discharged',
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public function isEditable()
    {
        if ($this->booking->status == Booking::STATUS_COMPLETED && $this->status == self::STATUS_PENDING && ($this->department_id == \Auth::user()->department->department_id && $this->treatment_date >= date('Y-m-d'))) {
            return true;
        }

        return false;
    }

    public function isSpecial()
    {
        return $this->is_special == self::IS_SPECIAL ? true : false;
    }

    public function getDetails($attr = "")
    {
        $details = PatientDetails::where('patient_id', $this->patient_id)->where(\DB::raw('date(`created_at`)'), (string)date("Y-m-d", strtotime($this->created_at)))->first();
        if ($details != null) {
            if ($attr == "weight") {
                return $details->weight;
            }
            if ($attr == "bp") {
                return $details->bp;
            }
            if ($attr == "pulse") {
                return $details->pulse;
            }
            return true;
        }

        return false;
    }

    public static function discharge($id, $b_id, $status)
    {
        $tokens = TreatmentToken::where("patient_id", $id)->where('booking_id', $b_id)->where('treatment_date', '>', (string)date("Y-m-d"))->get();
        if ($tokens->count() > 0) {
            foreach ($tokens as $token) {
                $token->deleteOldTreatments();
                $token->delete();
            }
        }

        $att_tokens = TreatmentToken::where("patient_id", $id)->where('status', TreatmentToken::STATUS_COMPLETED)->get();

        if ($att_tokens->count() > 0) {
            foreach ($att_tokens as $att_token) {
                $att_token->update([
                    // 'status' => TreatmentToken::STATUS_DISCHARGED,
                    'booking_id' => $b_id
                ]);
                $pat_treatments = $att_token->treatments;
                if ($pat_treatments->count() > 0) {
                    foreach ($pat_treatments as $pat_treatment) {
                        $pat_treatment->update([
                            //    'status' => PatientTreatment::STATUS_DISCHARGED
                        ]);
                    }
                }
            }
        }
    }

    public static function customDelete($id, $b_id)
    {
        $tokens = TreatmentToken::where("patient_id", $id)->where('booking_id', $b_id)->where('treatment_date', '>', (string)date("Y-m-d"))->get();
        if ($tokens->count() > 0) {
            foreach ($tokens as $token) {
                $token->deleteOldTreatments();
                $token->delete();
            }
        }

        $att_tokens = TreatmentToken::where("patient_id", $id)->where('booking_id', $b_id)->where('status', TreatmentToken::STATUS_COMPLETED)->get();

        if ($att_tokens->count() > 0) {
            foreach ($att_tokens as $att_token) {
                $pat_treatments = $att_token->treatments;
                if ($pat_treatments->count() > 0) {
                    foreach ($pat_treatments as $pat_treatment) {
                        $pat_treatment->delete();
                    }
                }
                $att_token->delete();
            }
        }
    }


}


