<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_DISCHARGED = 2;
    const STATUS_CANCELLED = 3;

    const PAYMENT_METHOD_WALLET = 1;
    const PAYMENT_METHOD_CREDIT = 2;
    const PAYMENT_METHOD_DEBIT = 3;
    const PAYMENT_METHOD_NET_BANKING = 4;
    const PAYMENT_METHOD_MOBILE_PAYMENTS = 5;


    protected $fillable = [
        'user_id',
        'booking_id',
        'txn_id',
        'amount',
        'transaction_data',
        'status',
        'payment_method',
        'discount_id',
        'discount_amount',
        'payable_amount'
    ];

    protected $table = 'transactions';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function items()
    {
        return $this->hasMany('App\OrderItem', 'transaction_id');
    }

    public function saveItems()
    {
        $userItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\User', 'item_id' => $this->user_id])->first();

        if ($userItem == null) {
            OrderItem::create(['transaction_id' => $this->id, 'item_type' => 'App\User', 'item_id' => $this->user_id, 'amount' => Settings::BASIC_PRICE, 'booking_id' => $this->booking_id]);
        }

        if ($this->booking_id != null) {

            $bookingItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\Booking', 'item_id' => $this->booking_id])->first();

            if ($bookingItem == null) {
                OrderItem::create(['transaction_id' => $this->id, 'item_type' => 'App\Booking', 'item_id' => $this->booking_id, 'amount' => $this->booking->daysPrice(), 'booking_id' => $this->booking_id]);
            }

            $services = $this->booking->services;

            if ($services != null) {
                foreach ($services as $service) {
                    $serviceItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\UserExtraService', 'item_id' => $service->id])->first();

                    if ($serviceItem == null) {
                        OrderItem::create(['transaction_id' => $this->id, 'item_type' => 'App\UserExtraService', 'item_id' => $service->id, 'amount' => $service->service->price, 'booking_id' => $this->booking_id]);
                    }
                }
            }
        }
    }

    public function getAccommodationAmount()
    {
        if ($this->booking_id != null) {
            $bookingItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\Booking', 'item_id' => $this->booking_id])->first();
        }
        $bookingItem = $this->getAccommodationItem();
        if ($bookingItem != null) {
            return $bookingItem->amount;
        }
    }

    public function getAmount()
    {
        $accomodation_amount = 0;
        $diet_amount = $this->user->getDietAmount();
        $treatment_amount = $this->user->getTreatmentsAmount();
        $amount = $this->amount + $diet_amount + $treatment_amount;
        $pending_amount = 0;
        $paid_amount = 0;
        $discount_amount = 0;
        $refund_amount = 0;


        $wallets = Wallet::where('user_id', $this->user_id)->where('type', Wallet::TYPE_PAID)->where('status', '!=', Wallet::STATUS_CLOSE)->get();
        foreach ($wallets as $wallet) {
            if ($wallet->status == Wallet::STATUS_PENDING)
                $pending_amount = $pending_amount + $wallet->amount;
            else
                $paid_amount = $paid_amount + $wallet->amount;
        }
       /* $pending_amount = $pending_amount + $treatment_amount + $diet_amount;

        if ($paid_amount < $pending_amount) {
            $pending_amount = $pending_amount - $paid_amount;
        }else{
            $pending_amount = 0;
        }*/

        $refund_wallet = Wallet::where('user_id', $this->user_id)->where('status', Wallet::STATUS_PENDING)->where('type', Wallet::TYPE_REFUND)->first();

        if ($refund_wallet != null) {
            $refund_amount = $refund_wallet->amount;
        }
        $refund_amount = $refund_amount - ($treatment_amount + $diet_amount);


        $service_amount = 0;
        if ($this->booking_id != null) {
            $bookingItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\Booking', 'item_id' => $this->booking_id])->first();
            if ($bookingItem != null) {
                if($this->booking != null)
                    $accomodation_amount = $this->booking->daysPrice();
            }
            if($this->booking != null) {
                $services = $this->booking->services;
                foreach ($services as $service) {
                    $bookingItem = OrderItem::where(['transaction_id' => $this->id, 'item_type' => 'App\UserExtraService', 'item_id' => $service->id])->first();
                    if ($bookingItem != null)
                        $service_amount += $bookingItem->amount;
                }
            }

        }

        if ($this->discount_amount != null) {
            $discount_amount = $this->discount_amount;
        }
        if ($refund_amount < 0) {
            $pending_amount = abs($refund_amount);
            $refund_amount = 0;
        }

        $arr = [
            'accomodation_amount' => $accomodation_amount,
            'amount' => $amount,
            'pending_amount' => $pending_amount,
            'paid_amount' => $paid_amount,
            'service_amount' => $service_amount,
            'discount_amount' => $discount_amount,
            'refund_amount' => $refund_amount,
        ];

        return $arr;
    }

    public function sendBookingEmail()
    {
        $message_string = "Your booking have been confirmed, Please login to view details";
        $title = "Patient Id: ".$this->user->userProfile->kid;
        $email = $this->user->email;
        \Mail::send('email.reply', ['title' => $title, 'message_string' => $message_string], function ($message) use($email, $title) {
            $message->from(env("USER_EMAIL"), 'Kayakalp');
            $message->subject($title);
            $message->to($email);
        });
    }

    public function getPaidAmount()
    {
        $wallet = Wallet::where('user_id', $this->user_id)->where('model_id', $this->id)->where("model_type", get_class($this))->first();
        if ($wallet != null)
            return $wallet->amount;
        return 0;
    }
}
