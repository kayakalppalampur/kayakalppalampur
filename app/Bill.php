<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_no', 'bill_date', 'booking_id', 'amount_paid'
    ];
    
    protected $table = 'patient_bills';

    public static function getRoutesArray()
    {
        return [
            'Laralum::admin.bills.create',
            'Laralum::admin.bills',
            'Laralum::admin.bills.edit',
            'Laralum::admin.bills.delete',
        ];
    }

    public function customDelete()
    {
        $this->delete();
    }

    public function booking(){
        return $this->belongsTo("App\Booking", "booking_id");
    }
    public function updateBillIds()
    {
        $tokens = OpdTokens::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
       
        foreach ($tokens as $token) {
            $token->bill_id = $this->id;
            $token->save();
        }
        $diets = $this->booking->getDietsWithoutBills();
        foreach ($diets as $diet) {
            $diet->bill_id = $this->id;
            $diet->save();
        }
          $treatments = TreatmentToken::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'));
          $treatment_tokens = $treatments->get();
          foreach ($treatment_tokens as $treatment_token) {
            $treatment_token->bill_id = $this->id;
            $treatment_token->save();
          }

          $lab_tests = PatientLabTest::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
          foreach ($lab_tests as $lab_test) {
            $lab_test->bill_id = $this->id;
            $lab_test->save();
          }

          $discounts = BookingDiscount::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
          foreach ($discounts as $discount) {
            $discount->bill_id = $this->id;
            $discount->save();
          }

          $payments = Wallet::where([
            'booking_id' => $this->booking_id,
            'status' => Wallet::STATUS_PAID
            ])->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();

        foreach ($payments as $payment) {
            $payment->bill_id = $this->id;
            $payment->save();
          }


          $misc = Misc::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->first();

		if ($misc) {
            $misc->bill_id = $this->id;
            $misc->save();
		}
        }

        public static function getID()
        {
            $bill = Bill::orderBy('id', 'desc')->first();
            if ($bill) {
                return $bill->bill_no + 1;
            }
            return 1;
        }
    
    




}
