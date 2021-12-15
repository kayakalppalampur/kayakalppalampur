<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemQuantityLog extends Model
{
    //

    const ACTION_ADDED = 1;
    const ACTION_REMOVED = 2;

    public static function getCurrent($id)
    {
        $added = ItemQuantityLog::where('item_id', $id)->where('action', self::ACTION_ADDED)->sum('qty');
        $removed = ItemQuantityLog::where('item_id', $id)->where('action', self::ACTION_REMOVED)->sum('qty');

        return $added - $removed;
    }

    public static function getActionOptions($id = null)
    {
        $list = [
            self::ACTION_ADDED => 'Add',
            self::ACTION_REMOVED => 'Remove',
        ];

        if ($id === null) {
            return $list;
        }

        return $list[$id];
    }

    public static function getActionDropDownList()
    {
        $models = self::getActionOptions();
        $uid = [];
        foreach ($models as $k => $v) {
            $uid[] = [
                'value' => $k,
                'show' => $v,
            ];
        }

        return $uid;
    }
}
