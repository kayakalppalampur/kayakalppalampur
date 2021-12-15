<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapySystemicExamination extends Model
{
    //

    CONST YES = 1;
    CONST NO = 2;

    /*Body Build Constant*/

    CONST ECTOMORPH = 1;
    CONST MESOMORPH = 2;
    CONST ENDOMORPH = 3;
    /*Gait posture constant*/

    CONST ABNORMAL = 1;
    CONST NORMAL = 2;

    CONST PITTING = 1;
    CONST NONPITTING = 2;


    protected $table = 'physiotherapy_systemic_examinations';


    protected $fillable = [
        'body_built',
        'gait',
        'posture',
        'posture_comment',
        'deformity',
        'deformity_comment',
        'tenderness',
        'tenderness_comment',
        'warmth',
        'warmth_comment',
        'swelling',
        'swelling_comment',
        'creiptus',
        'creiptus_comment',
        'muscle_spasm',
        'muscle_spasm_comment',
        'muscle_tightness',
        'muscle_tightness_comment',
        'edema',
        'booking_id',
        'patient_id',
        'created_by',
    ];


    public function setData($request)
    {
//dd($request);
        $body_built = is_array($request->get('body_built')) ? implode(',', $request->get('body_built')) : '';
        $this->body_built = $body_built;
        $gait = is_array($request->get('gait')) ? implode(',', $request->get('gait')) : '';
        $this->gait = $gait;
        $posture = is_array($request->get('posture')) ? implode(',', $request->get('posture')) : '';
   

        $this->posture = $posture;
        $this->posture_comment = $request->get('posture_comment');
        $this->deformity = $request->get('deformity');
        $this->deformity_comment = $request->get('deformity_comment');
        $this->tenderness = $request->get('tenderness');
        $this->tenderness_comment = $request->get('tenderness_comment');
        $this->warmth = $request->get('warmth');
        $this->warmth_comment = $request->get('warmth_comment');
        $this->swelling = $request->get('swelling');
        $this->swelling_comment = $request->get('swelling_comment');
        $this->creiptus = $request->get('creiptus');
        $this->creiptus_comment = $request->get('creiptus_comment');
        $this->muscle_spasm = $request->get('muscle_spasm');
        $this->muscle_spasm_comment = $request->get('muscle_spasm_comment');
        $this->muscle_tightness = $request->get('muscle_tightness');
        $this->muscle_tightness_comment = $request->get('muscle_tightness_comment');
        $edema = is_array($request->get('edema')) ? implode(',', $request->get('edema')) : '';
        $this->edema = $edema;
        $this->booking_id = $request->get('booking_id');
        $this->patient_id = $request->get('patient_id');
        $this->created_by = Auth::user()->id;
    }


    public function getEdema()
    {

        return [
            self::PITTING => 'Pitting',
            self::NONPITTING => 'Non Pitting',

        ];

    }


    public function getEdemaOption($key)
    {
        $list = $this->getEdema();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

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


    public function getGait()
    {

        return [
            self::ABNORMAL => 'Abnormal',
            self::NORMAL => 'Normal',

        ];


    }

    public function getGaitOption($key)
    {
        $list = $this->getGait();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

    public function getBodyBuild($id = null)
    {
        return [
            self::ECTOMORPH => 'Ectomorph',
            self::MESOMORPH => 'Mesomorph',
            self::ENDOMORPH => 'Endomorph',
        ];
    }

    public function getBodyBuildOption($key)
    {
        $list = $this->getBodyBuild();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;

    }

    public function getValue($attr)
    {
        if ($attr == 'body_built') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getBodyBuildOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'gait') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getGaitOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'posture') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getGaitOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'edema') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getEdemaOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($this->$attr == 1) {
            return 'Yes';
        }

        return 'No';
    }

}
