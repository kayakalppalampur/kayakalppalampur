<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    const TYPE_PAID = 0;
    const TYPE_REFUND = 1;

    const STATUS_PAID = 1;
    const STATUS_PENDING = 0;
    const STATUS_CLOSE = 2;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'model_id',
        'model_type',
        'txn_id',
        'created_by',
        'status',
        'payment_method',
        'booking_id',
        'description',
        'bill_id'
    ];
    protected $table = 'wallet';

    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }

    public function patient()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'model_id');
    }

    public function createUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function setData($request)
    {
        $this->user_id = $request->get('user_id');
        $this->amount = $request->get('amount');
        $this->created_by = \Auth::user()->id;
        $this->type = $request->get('type');
        $this->model_id = $request->get('model_id');
        $this->model_type = $request->get('model_type');
        $this->payment_method = $request->get('payment_method');
        $this->booking_id = $request->get('booking_id');
        $this->description = $request->get('description');
        return $this;
    }

    public static function getAllRelations()
    {
        return [
            'user',
            'user.userProfile',
        ];
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_CLOSE => 'Closed/Discharged',
            self::STATUS_PENDING => 'Payment Pending',
            self::STATUS_PAID => 'Payment Completed',
        ];
        if ($id === null)
            return $list;

        return $list[$id];
    }

    public function getModelName()
    {
        /*return $this->model_type;*/
        $html = "<table class='wallet-table'>";
        if ($this->model_type == "App\DietDailyStatus") {
            $diet_status = DietDailyStatus::find($this->model_id);
            $html = '<tr><th> Date:'.$diet_status->date."</th>";
            if($diet_status->is_breakfast != DietDailyStatus::STATUS_PENDING) {
                $html .= "<td>Breakfast:<br/>";
                $items = DietChartItems::where('diet_id', $diet_status->diet_id)->where('type_id', DietChartItems::TYPE_BREAKFAST)->get();
                foreach ($items as $item) {
                    $html .= $item->item->name.":".$item->item_price."<br/>";
                }
                $html .= "</td>";
            }
            if($diet_status->is_lunch != DietDailyStatus::STATUS_PENDING) {
                $html .= "<td>Lunch:<br/>";
                $items = DietChartItems::where('diet_id', $diet_status->diet_id)->where('type_id', DietChartItems::TYPE_LUNCH)->get();
                foreach ($items as $item) {
                    $html .= $item->item->name.":".$item->item_price."<br/>";
                }
                $html .= "</td>";
            }
            if($diet_status->is_post_lunch != DietDailyStatus::STATUS_PENDING) {
                $html .= "<td>Post Lunch:<br/>";
                $items = DietChartItems::where('diet_id', $diet_status->diet_id)->where('type_id', DietChartItems::TYPE_POST_LUNCH)->get();
                foreach ($items as $item) {
                    $html .= $item->item->name.":".$item->item_price."<br/>";
                }
                $html .= "</td>";
            }
            if($diet_status->is_dinner != DietDailyStatus::STATUS_PENDING) {
                $html .= "<td>Dinner:<br/>";
                $items = DietChartItems::where('diet_id', $diet_status->diet_id)->where('type_id', DietChartItems::TYPE_DINNER)->get();
                foreach ($items as $item) {
                    $html .= $item->item->name.":".$item->item_price."<br/>";
                }
                $html .= "</td>";
            }
            if($diet_status->is_special != DietDailyStatus::STATUS_PENDING) {
                $html .= "<td>Breakfast:<br/>";
                $items = DietChartItems::where('diet_id', $diet_status->diet_id)->where('type_id', DietChartItems::TYPE_SPECIAL)->get();
                foreach ($items as $item) {
                    $html .= $item->item->name.":".$item->item_price."<br/>";
                }
                $html .= "</td>";
            }
            $html .= "</tr>";
        }elseif($this->model_type == TreatmentToken::class) {
            $token = TreatmentToken::find($this->model_id);
            $html = '<tr><th> Date:'.$token->treatment_date."</th>";
            $items = $token->treatments;
            foreach ($items as $item) {
                $html .= "<td>Treatment:<br/>";
                $html .= $item->treatment->title.":".$item->treatment->price."<br/>";
                $html .= "</td>";
            }
            $html .= "</tr>";
        }
        return $html;
    }


    public static function discharge($id)
    {
        $wallets = Wallet::where('user_id', $id)->where('status', '!=', Wallet::STATUS_CLOSE)->get();
        foreach ($wallets as $wallet) {
            $wallet->update([
                'status' => Wallet::STATUS_CLOSE
            ]);
        }
    }
    public static function customDelete($id)
    {
        $wallets = Wallet::where('booking_id', $id)->get();
        foreach ($wallets as $wallet) {
            $wallet->delete();
        }
    }

}
