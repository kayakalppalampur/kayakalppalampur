<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthIssue extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_DISCHARGED = 2;

    protected $fillable = [
        'user_id',
        'health_issues',
        'booking_id',
        'status'
    ];
    protected $table = 'health_issues';

    public function rules()
    {
        return [
            'user_id' => 'required',
            'health_issues' => 'required',
        ];
    }

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function setData($request)
    {
        $this->user_id = $request->get('user_id');
        $this->booking_id = $request->get('booking_type');
        $this->health_issues = $request->get('health_issues');
        $this->status = self::STATUS_PENDING;
    }
    public static function customDeleteBooking($b_id)
    {
        $models = self::where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public static function getStatusOptions($id = null)
    {
        $list = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DISCHARGED => 'Discharged'
        ];

        if ($id === null)
            return $list;

        return $list[$id];
    }


}
