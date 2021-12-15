<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapyPainAssesment extends Model
{


    /*type of pain*/
    CONST SHARP = 7;
    CONST DULL = 1;
    CONST THROBBING = 2;
    CONST NUMBNESS = 3;
    CONST SHOOTING = 4;
    CONST BURNING = 5;
    CONST TIGGLINNG = 6;





    /*nature of pain*/

    CONST CONSTANT = 1;
    CONST OCCASSIONAL = 2;
    CONST FREQUENT = 3;
    CONST INTERMITTENT = 4;


    /*symptoms are worse*/

    CONST MORNING = 1;
    CONST AFTERNOON = 2;
    CONST NIGHT = 3;
    CONST SAME_ALL_DAY = 4;


    protected $table = 'physiotherapy_pain_assesments';


    protected $fillable = [
        'pain_at_rest',
        'pain_with_activity',
        'pain_at_night',
        'relieving_factor',
        'type_of_pain',
        'nature_of_pain',
        'symptoms_are_worse',
        'booking_id',
        'patient_id',
        'created_by',
        'aggregation_factor'

    ];


    public function getTypeOfPain()
    {

        return [
            self::SHARP => 'Sharp',
            self::DULL => 'Dull',
            self::THROBBING => 'Throbbing',
            self::NUMBNESS => 'Numbness',
            self::SHOOTING => 'Shooting',
            self::BURNING => 'Burning',
            self::TIGGLINNG => 'Tingling'
        ];

    }

    public function getTypeOfPainOption($key)
    {
        $list = $this->getTypeOfPain();
        return $value = isset($list[$key]) ? $list[$key] : '';
    }


    public function getNature()
    {

        return [
            self::CONSTANT => 'Constant',
            self::OCCASSIONAL => 'Occassional',
            self::FREQUENT => 'Frequent',
            self::INTERMITTENT => 'Intermittent',

        ];

    }


    public function getNatureOption($key)
    {

        $list = $this->getNature();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }


    public function getSymptoms()
    {
        return [
            self::MORNING => 'Morning',
            self::AFTERNOON => 'Afternoon',
            self::NIGHT => 'Night',
            self::SAME_ALL_DAY => 'Same all day',

        ];
    }


    public function getSymptomsOption($key)
    {

        $list = $this->getSymptoms();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }


    public function setData($request)
    {
        $this->pain_at_rest = $request->get('pain_at_rest');
        $this->pain_with_activity = $request->get('pain_with_activity');
        $this->pain_at_night = $request->get('pain_at_night');
        $this->aggregation_factor = $request->get('aggregation_factor');
        $this->relieving_factor = $request->get('relieving_factor');
        if($request->has('type_of_pain')){
            $type_of_pain = implode(',', $request->get('type_of_pain'));
            $this->type_of_pain = $type_of_pain;
        }
        if($request->has('nature_of_pain')){
            $nature_of_pain = implode(',', $request->get('nature_of_pain'));
            $this->nature_of_pain = $nature_of_pain;
        }
        if($request->has('symptoms_are_worse')){
            $symptoms_are_worse = implode(',', $request->get('symptoms_are_worse'));
            $this->symptoms_are_worse = $symptoms_are_worse;
        }
        $this->booking_id = $request->get('booking_id');
        $this->patient_id = $request->get('patient_id');
        $this->created_by = Auth::user()->id;
    }

    public function getValue($attr)
    {
        if ($attr == 'type_of_pain') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
               $value_ar[] = $this->getTypeOfPainOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'nature_of_pain') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getNatureOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'symptoms_are_worse') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSymptomsOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($this->$attr == 1) {
            return 'Yes';
        }

        return 'No';
    }

}
