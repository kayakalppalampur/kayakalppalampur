<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapyPainExamination extends Model
{


    /*constant of side*/
    CONST RIGHT = 1;
    CONST LEFT = 2;
    CONST BOTH = 3;
    /*constant of state*/

    CONST YES = 1;
    CONST NO = 2;
    protected $table = 'physiotherapy_pain_examinations';

    protected $fillable = [
        'muscle_pain',
        'muscle_pain_comment',
        'back_pain',
        'back_pain_comment',
        'knee_pain',
        'knee_pain_comment',
        'joint_pain',
        'joint_pain_comment',
        'spinal_injuries',
        'spinal_injuries_comment',
        'joint_stiffness',
        'joint_stiffness_comment',
        'side',
        'onset_of_symptoms',
        'priorities_injuries_to_affected_area',
        'priorities_injuries_to_affected_area_comment',
        'booking_id',
        'patient_id',
        'created_by',
    ];


    public function getState()
    {
        return [
            self::YES => 'Yes',
            self::NO => 'No',

        ];
    }

    public function getStateOption($key)
    {
        $list = $this->getState();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

    public function getSide()
    {
        return [
            self::LEFT => 'Left',
            self::RIGHT => 'Right',
            self::BOTH => 'Both',

        ];
    }


    public function getSideOption($key)
    {
        $list = $this->getSide();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

    public function setData($request)
    {
        $this->muscle_pain = $request->get('muscle_pain')[0];
        $this->muscle_pain_comment = $request->get('muscle_pain_comment');
        $this->back_pain = $request->get('back_pain')[0];
        $this->back_pain_comment = $request->get('back_pain_comment');
        $this->knee_pain = $request->get('knee_pain')[0];
        $this->knee_pain_comment = $request->get('knee_pain_comment');
        $this->joint_pain = $request->get('joint_pain')[0];
        $this->joint_pain_comment = $request->get('joint_pain_comment');
        $this->spinal_injuries = $request->get('spinal_injuries')[0];
        $this->spinal_injuries_comment = $request->get('spinal_injuries_comment');
        $this->joint_stiffness = $request->get('joint_stiffness')[0];
        $this->joint_stiffness_comment = $request->get('joint_stiffness_comment');
        $this->side = $request->get('side')[0];
        $this->onset_of_symptoms = $request->get('onset_of_symptoms');
        $this->priorities_injuries_to_affected_area = $request->get('priorities_injuries_to_affected_area')[0];
        $this->priorities_injuries_to_affected_area_comment = $request->get('priorities_injuries_to_affected_area_comment');
        $this->booking_id = $request->get('booking_id');
        $this->patient_id = $request->get('patient_id');
        $this->created_by = Auth::user()->id;
    }

    public function getValue($attr)
    {

        if ($attr == 'side') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            //print_r($values);

            foreach ($values as $value) {
                $value_ar[] = $this->getSideOption($value);
            }

            return implode(',', $value_ar);
        }
        elseif($attr == 'priorities_injuries_to_affected_area'){
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getStateOption($value);
            }

            return implode(',', $value_ar);
        }
        else{
            if ($this->$attr == 1) {
                return 'Yes';
            }

            return 'No';
        }
        

        return 'No';
    }

}
