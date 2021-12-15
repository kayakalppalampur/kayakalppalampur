<?php

namespace App;

use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Model;

class DischargePatient extends Model
{
    //

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'patient_id',
        'token_id',
        'doctor_id',
        'date_of_arrival',
        'date_of_discharge',
        'diagnosis',
        'discharge_summary',
        'investigation_report',
        'vital_data_id',
        'summary',
        'things_to_avoid',
        'follow_up_advice',
        'diet_plan_duration',
        'diet_plan_id',
        'followup_id',
        'status',
        'booking_id',
        'recommend_exercise'
    ];

    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id');
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function doctor()
    {
        return $this->belongsTo('App\User', 'doctor_id');
    }

    public function token()
    {
        return $this->belongsTo('App\PatientToken', 'token_id');
    }

    public function vitalData()
    {
        return $this->hasOne('App\PatientDetails', 'vital_data_id');
    }

    public function followup()
    {
        return $this->belongsTo('App\PatientFollowUp', 'followup_id');
    }

    public function booking_id()
    {
        return $this->belongsTo('App\\Booking', 'booking_id');
    }

    public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get('patient_id');
        $this->token_id = $request->get('token_id');
        $this->doctor_id = \Auth::user()->department->department_id;
        $this->date_of_arrival = $request->get('date_of_arrival');
        $this->date_of_discharge = date("Y-m-d"); //$request->get('date_of_discharge');
        $this->diagnosis = $request->get('diagnosis') ? $request->get('diagnosis') : "";
        $this->discharge_summary = $request->get('discharge_summary') ? $request->get('discharge_summary') : "";
        $this->investigation_report = $request->get('investigation_report') ? $request->get('investigation_report') : "";
        /*$this->vital_data_id  = $request->get('vital_data_id');*/
        $this->summary = $request->get('summary');
        $this->things_to_avoid = $request->get('things_to_avoid');
        $this->follow_up_advice = $request->get('follow_up_advice');
        $this->status = self::STATUS_PENDING;
        $this->diet_plan_duration = $request->get('diet_plan_duration');
        $this->recommend_exercise = $request->get('recommend_exercise');

        /*$this->diet_plan_id  = $request->get('diet_plan_id');*/
        /*$this->followup_id  = $request->get('followup_id');*/
        return $this;
    }

    public static function getAllRelations()
    {
        return [
            'patient',
            'patient.userProfile',
            'doctor',
            'doctor.department',
            'vitalData',
            'token'
        ];
    }

    public function saveFollowup($days, $date = null)
    {
        if (($days != "" && $days != 0) || $date != null) {
            if ($date == null) {
                $date = date('Y-m-d', strtotime('+' . $days . " days"));
            }

            $followup = PatientFollowUp::where([
                'followup_date' => $date,
                'patient_id' => $this->id,
                'doctor_id' => \Auth::user()->id,
                'department_id' => \Auth::user()->department->id
            ])->first();

            if ($followup == null) {
                $followup = new PatientFollowUp();
            }

            $followup = PatientFollowUp::create([
                'followup_date' => $date,
                'patient_id' => $this->id,
                'doctor_id' => \Auth::user()->id,
                'department_id' => \Auth::user()->department->id
            ]);
            $this->update([
                'followup_id' => $followup->id
            ]);
        }
    }

    public function saveVitalData($request)
    {
        $patient_details = PatientDetails::where("patient_id", $this->patient_id)->where('type', PatientDetails::TYPE_DISCHARGE)->orderBy('created_at', 'DESC')->first();
        $ok = false;

        if ($patient_details != null) {
            if ($patient_details->created_by == \Auth::user()->id) {
                $ok = true;
            }
        } else {
            $ok = true;
            $patient_details = new PatientDetails();
        }

        if ($ok == true) {
            $patient_details->setData($request);
            $patient_details->type = PatientDetails::TYPE_DISCHARGE;
            if ($patient_details->save()) {
                $this->update([
                    'vital_data_id' => $patient_details->id
                ]);
            }
        }
    }

    public function getFollowupDays()
    {
        if ($this->followup != null) {
            if ($this->date_of_discharge == "0000-00-00") {
                $this->date_of_discharge = date("Y-m-d");
            }
            $now = strtotime($this->date_of_discharge); // or your date as well


            $your_date = strtotime($this->followup->followup_date);
            $datediff = $your_date - $now;

            return floor($datediff / (60 * 60 * 24));
        }
        return 0;
    }

    public function getFollowupDate()
    {
        if ($this->followup != null) {
            return $this->followup->followup_date;
        }
        return 0;
    }

    public function saveNewBooking()
    {
        $transaction = $this->patient->getTransaction();
        $booking = $transaction->booking;
        $transaction->update([
            'status' => Transaction::STATUS_CANCELLED
        ]);
        $booking->update([
            'status' => Booking::STATUS_CANCELLED
        ]);
        $booking->cancelServices();
        $service_ids_ar = $booking->getServiceIdAr();
        $new_booking = Booking::create([
            'user_id' => $this->patient_id,
            'room_id' => $booking->room_id,
            'booking_type' => $booking->booking_type,
            'check_in_date' => $booking->check_in_date,
            'check_out_date' => $this->date_of_discharge,
            'status' => Booking::STATUS_COMPLETED
        ]);
        if ($new_booking->daysPrice() > 0)
            $new_booking->saveServices($service_ids_ar);

        $new_amount = Settings::BASIC_PRICE + $new_booking->daysPrice() + $new_booking->getServicePrices();
        $payable = $new_amount - $transaction->discount_amount;
        $new_transaction = Transaction::create([
            'user_id' => $this->patient_id,
            'booking_id' => $new_booking->id,
            'txn_id' => 'Transaction---',
            'amount' => $new_amount,
            'transaction_data' => '',
            'status' => Transaction::STATUS_COMPLETED,
            'payment_method' => '',
            'discount_id' => $transaction->discount_id,
            'discount_amount' => $transaction->discount_amount,
            'payable_amount' => $payable
        ]);
        $new_transaction->saveItems();

        $refund_amount = $transaction->amount - $transaction->discount_amount;
        if ($payable > $refund_amount) {
            $amount = $payable - $refund_amount;
            Wallet::create([
                'user_id' => $this->patient_id,
                'amount' => $amount,
                'type' => Wallet::TYPE_PAID,
                'model_id' => $new_transaction->id,
                'model_type' => Transaction::class,
                'txn_id' => 'sdsf',
                'created_by' => \Auth::user()->id,
                'status' => Wallet::STATUS_PENDING
            ]);
        } else {
            $amount = $refund_amount - $payable;
            $wallet = Wallet::where([
                'user_id' => $this->patient_id,
                'type' => Wallet::TYPE_REFUND,
                'status' => Wallet::STATUS_PENDING
            ])->first();
            if ($wallet == null)
                $wallet = new Wallet();
            $wallet->user_id = $this->patient_id;
            $wallet->amount = $wallet->amount + $amount;
            $wallet->type = Wallet::TYPE_REFUND;
            $wallet->model_id = $new_transaction->id;
            $wallet->model_type = Transaction::class;
            $wallet->txn_id = '';
            $wallet->created_by = \Auth::user()->id;
            $wallet->status = Wallet::STATUS_PENDING;
            $wallet->save();
        }
    }

    public static function getDischargeReports($id)
    {
        $reports = DischargePatient::where('doctor_id', '!=', \Auth::user()->id)->where('status', DischargePatient::STATUS_PENDING)->where('patient_id', $id)->get();
        if ($reports->count() > 0)
            return $reports;
        return false;
    }

    public function isEditable()
    {
        if ($this->id != null) {
            /*if (\Auth::user()->id == $this->doctor_id) {*/
                return true;
           // }
            return false;
        }

        return true;
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

}
