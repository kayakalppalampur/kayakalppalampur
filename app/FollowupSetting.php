<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowupSetting extends Model
{

    const TYPE_HOURS = 1;
    const TYPE_MINUTES = 0;
    const TYPE_DAYS = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'period',
        'period_type',
        'template_id',
    ];

    protected $table = 'follow_ups_settings';

    public function rules()
    {
        $rules = [
            'period' => 'required',
            'period_type' => 'required',
            'template_id' => 'required',
        ];

        return $rules;
    }

    public function template()
    {
        return $this->belongsTo('App\EmailTemplate', 'template_id');
    }

    public function setData($data)
    {
        $this->period = $data->get('period');
        $this->period_type = $data->get('period_type');
        $this->template_id = $data->get('template_id');
        return $this;
    }

    public static function getPeriodTypeOptions($id = null)
    {
        $list = [
            self::TYPE_MINUTES => "Minutes",
            self::TYPE_HOURS => "Hours",
            self::TYPE_DAYS => "Days",
        ];

        if ($id === null)
            return $list;

        if (isset($list[$id]))
            return $list[$id];

        return $id;
    }

    public function getPeriod()
    {
        return $this->period.' '.$this->getPeriodTypeOptions($this->period_type);
    }

}
