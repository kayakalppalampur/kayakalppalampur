<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapySensoryExamination extends Model
{


    /*constant of sensation type*/
    CONST INTACT = 1;
    CONST IMPAIRED = 2;
    /*constant of motor examination*/




    protected $table = 'physiotherapy_sensory_examinations';


    protected $fillable = [
        'superficial_sensation',
        'superficial_sensation_comment',
        'deep_sensation',
        'deep_sensation_comment',
        'hot_or_cold_sensation',
        'hot_or_cold_sensation_comment',
        'booking_id',
        'patient_id',
        'created_by',
    ];

    public function getSensationType()
    {
        return [
            self::INTACT => 'Intact',
            self::IMPAIRED => 'Impaired',

        ];
    }

    public function getSensationTypeOption($key)
    {
        $list = $this->getSensationType();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function setData($request)
    {
        $superficial_sensation =  is_array($request->get('superficial_sensation')) ?  implode(',', $request->get('superficial_sensation')) : '';
        $this->superficial_sensation = $superficial_sensation;
        $this->superficial_sensation_comment = $request->get('superficial_sensation_comment');
        if($request->has('deep_sensation')){
            if(is_array($request->get('deep_sensation'))){
                $deep_sensation = implode(',', $request->get('deep_sensation'));
                $this->deep_sensation = $deep_sensation;
            } 
        }


        $this->deep_sensation_comment = $request->get('deep_sensation_comment');
        if($request->has('hot_or_cold_sensation')){
            if(is_array($request->get('hot_or_cold_sensation'))){
                $hot_or_cold_sensation = implode(',', $request->get('hot_or_cold_sensation'));
                $this->hot_or_cold_sensation = $hot_or_cold_sensation;
            } 
        }


        $this->hot_or_cold_sensation_comment = $request->get('hot_or_cold_sensation_comment');
        $this->booking_id = $request->get('booking_id');
        $this->patient_id = $request->get('patient_id');
        $this->created_by = Auth::user()->id;
    }

    public function getValue($attr)
    {
        if ($attr == 'superficial_sensation') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSensationTypeOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'deep_sensation') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSensationTypeOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'hot_or_cold_sensation') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSensationTypeOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($this->$attr == 1) {
            return 'Yes';
        }

        return 'No';
    }




}
