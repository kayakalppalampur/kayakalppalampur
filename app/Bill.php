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
            'Laralum::bills',
            'Laralum::bills.view',
            'Laralum::bills.delete',
            'Laralum::bills.print',
            'Laralum::bills.bill_print',
        ];
    }

    public function customDelete()
    {
        $this->delete();
        return true;
    }

    public function booking(){
        return $this->belongsTo("App\Booking", "booking_id");
    }
    
    public function opdToken() {
        return $this->belongsTo("App\OpdTokens", "opd_token_id");
    }

    public function updateBillIds()
    {
        $tokens = OpdTokens::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        $diets = $this->booking->getDietsWithoutBills();
        $treatments = TreatmentToken::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'));
        $treatment_tokens = $treatments->get();
      
        $lab_tests = PatientLabTest::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
         
        $discounts = BookingDiscount::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();
        
        $misc = Misc::where('booking_id', $this->booking_id)->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->first();

        $payments = Wallet::where([
            'booking_id' => $this->booking_id,
            'status' => Wallet::STATUS_PAID
            ])->doesntHave('bill')->where('created_at', '<=', date('Y-m-d H:i:s'))->get();

        foreach ($tokens as $token) {
            $token->bill_id = $this->id;
            $token->save();
        }

        foreach ($diets as $diet) {
            $diet->bill_id = $this->id;
            $diet->save();
        }

        foreach ($treatment_tokens as $treatment_token) {
            $treatment_token->bill_id = $this->id;
            $treatment_token->save();
        }

        foreach ($lab_tests as $lab_test) {
            $lab_test->bill_id = $this->id;
            $lab_test->save();
        } 
        foreach ($discounts as $discount) {
            $discount->bill_id = $this->id;
            $discount->save();
        }
        foreach ($payments as $payment) {
            $payment->bill_id = $this->id;
            $payment->save();
        }
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
    
    
        public static function generateOpdTokenBill($token){
            $bill = new Bill();
            $bill->opd_token_id = $token->id;
            $bill->created_by = \Auth::user()->id;
            $bill->bill_no = Bill::getID();
            $bill->bill_date = date("d-m-Y");   
            $bill->opd_consultation = $token->charges;

            $bill->booking_id = $token->booking_id;
            $bill->bill_amount = $token->charges;
            if($bill->save()) {
                if ($bill->id != null) {
                    $token->bill_id = $bill->id;
                }
            }
            return $bill;
        }



}
