<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Monolog\Handler\IFTTTHandler;

class Treatment extends Model
{

    const TYPE_MINUTES = 0;
    const TYPE_HOURS = 1;

    protected $fillable = [
        'duration', 'title', 'type', 'price', 'department_id'
    ];

    protected $table = 'treatments';

    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public static function getRules()
    {
        return [
            'title' => 'required',
            /*'quantity' => 'required',*/
            'duration' => 'required',
            'department_id' => 'required'
            /* 'price' => 'required'*/
        ];
    }

    public function setData($data)
    {
        $this->title = $data->get("title");
        $this->duration = $data->get("duration");
        $this->type = $data->get("type");
        $this->department_id = $data->get("department_id");
        $this->price = $data->get('price') ? $data->get("price") : 0;
        return $this;
    }

    public static function getDurationTypesDropDownList()
    {
        $list = [
            [
                'value' => self::TYPE_MINUTES,
                'show' => "m",
            ],
            [
            'value' => self::TYPE_HOURS,
                'show' => "h",
            ],
        ];

        return $list;
    }

    public function getDuration()
    {
        if ($this->type == self::TYPE_HOURS) {
           return $this->duration . " h";
       }
        return $this->duration . " m";
    }

    public static function getTotalDuration($ids)
    {
        $treatments = Treatment::whereIn('id', $ids)->get();
        $duration = 0;
        foreach ($treatments as $treatment) {
            $t_duration = $treatment->duration;
            if ($treatment->type == Treatment::TYPE_HOURS) {
                $t_duration = $treatment->duration * 60;
            }
            $duration = $duration + $t_duration;
        }
        $format = '%02d h %02d m';
        $time = Settings::convertToHoursMins($duration, $format);
        return $time;
    }

    public static function getTreatments()
    {
        $treatments = Treatment::where('department_id', \Auth::user()->department->department_id)->Orwhere('department_id',0)->get();
        return $treatments;
    }

    public function showfield($field)
    {
        if ($field == 'type') {
            return "Minutes/Hours";
        }
        return $field;
    }

    public static function getRoutesArray()
    {
        return [
            'Laralum::treatments',
            'Laralum::treatments.print',
            'Laralum::treatments.export',
            'Laralum::treatment.create',
            'Laralum::treatment.edit',
            'Laralum::treatment.delete',
            'Laralum::treatment_packages',
            'Laralum::treatment_packages.search',
            'Laralum::treatment_packages.create',
            'Laralum::treatment_packages.edit',
            'Laralum::treatment_packages.delete'
        ];
    }

    public static function getAccountRoutesArray()
    {
        return [
            'Laralum::treatment_tokens',
        ];
    }

    public static function importData($arr, $dept)
    {
        $model = Treatment::where('title', $arr["title_treatment_name"])->first();

        if ($model == null) {
            $model = new Treatment();
        }

        $model->title = $arr["title_treatment_name"];
        $model->duration = $arr["duration"];
        $type = self::TYPE_HOURS;

        if ($arr['minuteshours'] == 'm') {
            $type = self::TYPE_MINUTES;
        }

        $model->type = $type;
        $model->department_id = $dept;
        $model->price = $arr['price'];
        $model->save();
    }
}

