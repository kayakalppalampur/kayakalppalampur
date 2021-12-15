<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'message',
        'model_id',
        'model_type',
        'receiver_id',
        'receiver_type',
        'created_by',
        'status',
        'type_id',
    ];


    const STATUS_PENDING = 0;
    const STATUS_READ = 1;

    protected $table = 'notifications';

    public static function saveNotification($model, $message, $id = null)
    {
        $u_id = $id;
        if (\Auth::check())
            $u_id = \Auth::user()->id;

        Notification::create([
            'message' => $message,
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'receiver_id' => $id,
            'created_by' => $u_id,
            'status' => self::STATUS_PENDING,
        ]);
    }

    public static function getNotificationCount($class)
    {
        $notifications = Notification::where("model_type", $class)->where("status", Notification::STATUS_PENDING)->count();

        return $notifications;

    }

    public static function updateNotification($class)
    {
        $notifications = Notification::where("model_type", $class)->where("status", Notification::STATUS_PENDING)->get();
        if($notifications->count() > 0) {
            foreach ($notifications as $notification) {
                $notification->update([
                    'status' => self::STATUS_READ
                ]);
            }
        }
    }
}
