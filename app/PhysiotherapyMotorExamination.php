<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapyMotorExamination extends Model
{

    CONST FULL = 1;
    CONST RESTRICTED = 2;
    /*constant of reflex*/

    CONST PRESENT = 1;
    CONST ABSENT = 2;


    CONST HYPERTONIA = 1;
    CONST HYPOTONIA = 2;

    CONST INTACT = 2;
    CONST IMPAIRED = 1;

    CONST NORMAL = 2;
    CONST REDUCED = 1;

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

    public function getMuscleType()
    {

        return [
            self::NORMAL => 'Normal',
            self::REDUCED => 'Reduced',

        ];
    }


    public function getMuscleTypeOption($key)
    {
        $list = $this->getMuscleType();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }


    protected $table = 'physiotherapy_motor_examinations';
    protected $fillable = [
        'rom_of_joint',
        'rom_of_joint_type',
        'flexion_values',
        'muscle_power_grade',
        'muscle_power_grade_comment',
        'muscle_power_tone',
        'muscle_power_tone_comment',
        'deep_reflexes',
        'deep_reflexes_comment',
        'superficial_reflexes',
        'superficial_reflexes_comment',
        'bower_and_bladder',
        'bower_and_bladder_comment',
        'specific_test',
        'provisional_diagonosis',
        'booking_id',
        'patient_id',
        'created_by',
        'joint_id',
        'joint_sub_category_id',
        'joint_right_side',
        'joint_left_side',
    ];


    public function getState()
    {
        return [
            self::FULL => 'Full',
            self::RESTRICTED => 'Restricted',

        ];
    }

    public function getStateOption($key)
    {
        $list = $this->getState();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }


    public function getMuscleTone()
    {
        return [
            self::HYPERTONIA => 'Hypertonia',
            self::HYPOTONIA => 'Hypotonia',
        ];
    }

    public function getMuscleToneOption($key)
    {
        $list = $this->getMuscleTone();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

    public function getReflex()
    {
        return [
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',

        ];
    }

    public function getReflexOption($key)
    {
        $list = $this->getReflex();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function setData($request)
    {
//        $this->rom_of_joint = $request->get('rom_of_joint')[0];
        $rom_joints = is_array($request->get('rom_of_joint')) ?  $request->get('rom_of_joint') : array();
        $rom_of_joint = implode(',', $rom_joints);
        $this->rom_of_joint = $rom_of_joint;
        $this->rom_of_joint_type = $request->get('rom_of_joint_type');
        $this->flexion_values = $request->get('flexion_values');

        $muscle_power_grade_ar = is_array($request->get('muscle_power_grade')) ? $request->get('muscle_power_grade') : array();

        $muscle_power_grade = implode(',', $muscle_power_grade_ar);
        $this->muscle_power_grade = $muscle_power_grade;
        $this->muscle_power_grade_comment = $request->get('muscle_power_grade_comment');

        $muscle_power_tone_ar = is_array($request->get('muscle_power_tone')) ? $request->get('muscle_power_tone') : array();
        $muscle_power_tone = implode(',', $muscle_power_tone_ar);
        $this->muscle_power_tone = $muscle_power_tone;
        $this->muscle_power_tone_comment = $request->get('muscle_power_tone_comment');

        $deep_reflexes_ar = is_array($request->get('deep_reflexes')) ? $request->get('deep_reflexes') : array();
        $deep_reflexes = implode(',', $deep_reflexes_ar);
        $this->deep_reflexes = $deep_reflexes;
        $this->deep_reflexes_comment = $request->get('deep_reflexes_comment');

        $superficial_reflexes_ar = is_array($request->get('superficial_reflexes')) ? $request->get('superficial_reflexes') : array();
        $superficial_reflexes = implode(',', $superficial_reflexes_ar);
        $this->superficial_reflexes = $superficial_reflexes;
        $this->superficial_reflexes_comment = $request->get('superficial_reflexes_comment');

        $bower_and_bladder_ar = is_array($request->get('bower_and_bladder')) ? $request->get('bower_and_bladder') : array();
        $bower_and_bladder = implode(',', $bower_and_bladder_ar);
        $this->bower_and_bladder = $bower_and_bladder;
        $this->bower_and_bladder_comment = $request->get('bower_and_bladder_comment');

        $this->specific_test = $request->get('specific_test');
        $this->provisional_diagonosis = $request->get('provisional_diagonosis');
        $this->booking_id = $request->get('booking_id');
        $this->patient_id = $request->get('patient_id');
        $this->created_by = Auth::user()->id;
        $data = array();

        if(!empty($request->joint)) {
            foreach ($request->joint as $key => $joint) {
                $data['all_joints'][] = $joint;
                $data['subcat'][] = isset($request->subcat[$key]) ? $request->subcat[$key] : "";
                $data['right'][] = $request->right[$key];
                $data['left'][] = $request->left[$key];
            }
            $this->joint_id = implode(',', $data['all_joints']);
            $this->joint_sub_category_id = implode(',', $data['subcat']);
            $this->joint_right_side = implode(',', $data['right']);
            $this->joint_left_side = implode(',', $data['left']);
        }

    }


    public function getValue($attr)
    {
        if ($attr == 'rom_of_joint') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getStateOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'muscle_power_grade') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMuscleTypeOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'muscle_power_tone') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMuscleToneOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'deep_reflexes') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getReflexOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'superficial_reflexes') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getReflexOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'bower_and_bladder') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSensationTypeOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($this->attr == 1) {
            return 'Yes';
        }

        return 'No';
    }

    public function getJoints()
    {
        $joints = explode(',', $this->joint_id);
        $joint_sub_categories = explode(',', $this->joint_sub_category_id);
        $right = explode(',', $this->joint_right_side);
        $left = explode(',', $this->joint_left_side);

        $joint_array = [];

        foreach ($joints as $key => $joint) {
            $joint_model = RomJoint::find($joint);
            if ($joint_model) {
                $joint_sub_category_id = RomSubCategory::find($joint_sub_categories[$key]);
                if($joint_sub_category_id){
                   // $joint_sub_category_id->sub_category =
                    $joint_array[$key] = [
                        'joint' => $joint_model->joint_name,
                        'joint_sub_category' => $joint_sub_category_id->sub_category,
                        'normal_rom' => $joint_sub_category_id->normal_rom,
                        'right' => $right[$key],
                        'left' => $left[$key]
                    ];
                }

            }
        }

        return $joint_array;
    }
}
