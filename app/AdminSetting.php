<?php

namespace App;

use function GuzzleHttp\Promise\is_rejected;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $table = 'admin_settings';

	protected $fillable = [
        'setting_name',
        'setting_name_slug',
        'price',
	];

	public static function getSettingPrice($slug)
    {
        $setting = AdminSetting::where('setting_name_slug', $slug)->first();

        if ($setting) {
            return $setting->price;
        }

        return 0;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::price-settings',
            'Laralum::price-settings.print',
            'Laralum::price-settings.export',
        ];
    }

}
