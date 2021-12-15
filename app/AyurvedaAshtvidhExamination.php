<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AyurvedaAshtvidhExamination extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    const TYPE_CONSISTANT = 0;
    const TYPE_INCONSISTANT = 1;

    const TYPE_SAAM = 0;
    const TYPE_NIRAM = 1;
    const TYPE_SHUSHK = 2;
    const TYPE_LIPT = 3;

    const TYPE_UNNATURAL = 0;
    const TYPE_NATURAL= 1;

    const SLIM = 0;
    const FATTISH = 1;
    const NORMAL = 2;

    const VAAT = 0;
    const PITT = 1;
    const COUGH = 2;
    const VAATPITT = 3;
    const PITTCOUGH = 4;
    const COUGHVAAT = 5;
    const SUM = 6;



    protected $fillable = [
        'patient_id',
        'status',      
        'created_by',
        'pulse',
        'pulse_issue',
        'pulse_comment',
        'faecal_matter',
        'faecal_matter_speed_days',
        'faecal_matter_comment',
        'faecal_matter_liquid',
        'faecal_matter_liquid_speed_days',
        'faecal_matter_liquid_speed_nights',
        'faecal_matter_liquid_comment',
        'skin',
        'skin_comment',
        'eyes',
        'eyes_comment',
        'tongue',
        'tongue_2',
        'tongue_comment',
        'speech',
        'speech_comment',
        'body_build',
        'body_build_comment',
        'created_by',
        'booking_id'
    ];

    protected $table = 'ayurved_ashtvidh_examinations';

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function setData($request)
    {
        $this->patient_id = $request->get("patient_id");
        $this->booking_id = $request->get("booking_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->pulse = $request->get('pulse');
        $this->pulse_issue = $request->get('pulse_issue');
        $this->pulse_comment = $request->get('pulse_comment');
        $this->faecal_matter = $request->get('faecal_matter');
        $this->faecal_matter_speed_days = $request->get('faecal_matter_speed_days');
        $this->faecal_matter_comment = $request->get('faecal_matter_comment');
        $this->faecal_matter_liquid = $request->get('faecal_matter_liquid');
        $this->faecal_matter_liquid_speed_days = $request->get('faecal_matter_liquid_speed_days');
        $this->faecal_matter_liquid_speed_nights = $request->get('faecal_matter_liquid_speed_nights');
        $this->faecal_matter_liquid_comment = $request->get('faecal_matter_liquid_comment');
        $this->skin = $request->get('skin');
        $this->skin_comment = $request->get('skin_comment');
        $this->eyes = $request->get('eyes');
        $this->eyes_comment = $request->get('eyes_comment');
        $this->tongue = $request->get('tongue');
        $this->tongue_2 = $request->get('tongue_2');
        $this->tongue_comment = $request->get('tongue_comment');
        $this->speech = $request->get('speech');
        $this->speech_comment = $request->get('speech_comment');
        $this->body_build = $request->get('body_build');
        $this->body_build_comment = $request->get('body_build_comment');
        return $this;
    }


    public static function discharge($id, $b_id, $status)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->update([
                'status' => $status,
                'booking_id' => $b_id
            ]);
        }
    }

    public static function customDelete($id, $b_id)
    {
        $models = self::where('patient_id', $id)->where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
    }

    public function getType($id = null)
    {
        $list = [
            self::TYPE_CONSISTANT => trans('laralum.consistent'),
            self::TYPE_INCONSISTANT => trans('laralum.inconsistent'),
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getToungueType($id = null)
    {
        $list = [
            self::TYPE_SAAM => trans('laralum.saam'),
            self::TYPE_NIRAM => trans('laralum.niraam'),
            self::TYPE_SHUSHK => trans('laralum.shushk'),
            self::TYPE_LIPT => trans('laralum.lipt'),
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getSpeechType($id = null)
    {
        $list = [
            self::TYPE_NATURAL => trans('laralum.prakrut'),
            self::TYPE_UNNATURAL => trans('laralum.vikrut'),
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getSkinType($id = null)
    {
        $list = [
            self::TYPE_NATURAL => trans('laralum.prakrut'),
            self::TYPE_UNNATURAL => trans('laralum.vikrut'),
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getBodyType($id = null)
    {
        $list = [
            self::VAAT => trans('laralum.vat'),
            self::PITT => trans('laralum.pitt'),
            self::COUGH => trans('laralum.kaph'),
            self::VAATPITT => trans('laralum.vaatpitt'),
            self::PITTCOUGH => trans('laralum.pitt_kaph'),
            self::COUGHVAAT => trans('laralum.kaph_vaat'),
            self::SUM => trans('laralum.sam'),
        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getValue($attr)
    {
        if ($this->$attr === null ) {
            return "";
        }

        if ($attr == 'faecal_matter') {
            return $this->getType($this->$attr);
        }

        if ($attr == 'tongue') {
            return $this->getToungueType($this->$attr);
        }

        if ($attr == 'tongue_2') {
            return $this->getToungueType($this->$attr);
        }

        if ($attr == 'speech') {
            return $this->getSpeechType($this->$attr);
        }

        if ($attr == 'eyes') {
            return $this->getSpeechType($this->$attr);
        }

        if ($attr == 'body_build') {
            return $this->getBodyType($this->$attr);
        }
        if ($attr == 'skin') {
            return $this->getSkinType($this->$attr);
        }

        return $this->$attr;

    }


}
