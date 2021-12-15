<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AyurvedDoshExamination extends Model
{

    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    const TYPE_KASHRYA = 0;
    const TYPE_KRISHNTA = 1;
    const TYPE_USHN_ICHHA = 2;
    const TYPE_KAMP = 3;
    const TYPE_SHAKRUT_GRIH = 4;
    const TYPE_BAL_BHRANSH = 5;
    const TYPE_INDRIYE_BHRUNSH = 6;
    const TYPE_NIDRA_BHRUNSH = 7;
    const TYPE_PRALAP = 8;
    const TYPE_BHRAM = 9;
    const TYPE_DEENTA = 10;

    const TYPE_SAAD = 0;
    const TYPE_ALP_BHASHAN = 1;
    const TYPE_SANGYA_MOH = 2;
    const TYPE_KAPH_VRIDHI = 3;

    const TYPE_PEET_VIT = 0;
    const TYPE_PEET_MUTRA = 1;
    const TYPE_PEET_NETRA = 2;
    const TYPE_PEET_TWAK = 3;
    const TYPE_ATI_KSHUDA = 4;
    const TYPE_ATI_TRUSHNA = 5;
    const TYPE_DAAH = 6;
    const TYPE_ALP_NIDRA = 7;

    const TYPE_MAND_AGNI = 0;
    const TYPE_SHEET_PRATITI = 1;
    const TYPE_PRABHA_HANI = 2;

    const TYPE_AGNI_SAAD = 0;
    const TYPE_PRASEK = 1;
    const TYPE_ALSYA = 2;
    const TYPE_GAURAV = 3;
    const TYPE_KSHVATYA = 4;
    const TYPE_SHAITYA = 5;
    const TYPE_ANG_SHISHITHILTA = 6;
    const TYPE_KSHWAAS = 7;
    const TYPE_KAAS = 8;
    const TYPE_ATI_NIDRA = 9;

    const TYPE_KAPH_DOSH_DECAY_BHRAM = 0;
    const TYPE_KAPH_DOSH_DECAY_SHUNYATA = 1;
    const TYPE_HRITDRAV = 2;
    const TYPE_SANDHI_SHITHILTA = 3;
    const TYPE_ANTRDAAH = 4;
    const TYPE_JAAGRAN = 5;


    protected $fillable = [
        'patient_id',
        'status',
        'vat_dosh_growth',
        'vat_dosh_decay',
        'pitt_dosh_growth',
        'pitt_dosh_decay',
        'kaph_dosh_growth',
        'kaph_dosh_decay',
        'created_by',
        'booking_id'
    ];

    protected $table = 'ayurved_dosh_examinations';

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
        $vat_growth = is_array($request->get('vat_dosh_growth')) ? implode(',', $request->get('vat_dosh_growth')) : '';
        $this->vat_dosh_growth = $vat_growth;
        $vat_decay = is_array($request->get('vat_dosh_decay')) ? implode(',', $request->get('vat_dosh_decay')) : '';
        $this->vat_dosh_decay = $vat_decay;
        $pitt_growth = is_array($request->get('pitt_dosh_growth')) ? implode(',', $request->get('pitt_dosh_growth')) : '';
        $this->pitt_dosh_growth = $pitt_growth;
        $pitt_decay = is_array($request->get('pitt_dosh_decay')) ? implode(',', $request->get('pitt_dosh_decay')) : '';
        $this->pitt_dosh_decay = $pitt_decay;
        $kaph_growth = is_array($request->get('kaph_dosh_growth')) ? implode(',', $request->get('kaph_dosh_growth')) : '';
        $this->kaph_dosh_growth = $kaph_growth;
        $kaph_decay = is_array($request->get('kaph_dosh_decay')) ? implode(',', $request->get('kaph_dosh_decay')) : '';
        $this->kaph_dosh_decay = $kaph_decay;
        return $this;
    }

    public function isChecked($attr, $val)
    {
	if ($this->$attr == ''){
		return false;
        }
        $values = explode(",", $this->$attr);
        if(in_array($val, $values)) {
            return true;
        }
        return false;
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

    public function getVatGrowth()
    {
        return [
            self::TYPE_KASHRYA => trans('laralum.kashrya'),
            self::TYPE_KRISHNTA => trans('laralum.krishnta'),
            self::TYPE_USHN_ICHHA => trans('laralum.ushn_ichha'),
            self::TYPE_KAMP => trans('laralum.kamp'),
            self::TYPE_SHAKRUT_GRIH => trans('laralum.shakrut_grih'),
            self::TYPE_BAL_BHRANSH => trans('laralum.bal_bhransh'),
            self::TYPE_INDRIYE_BHRUNSH => trans('laralum.indriye_bhrunsh'),
            self::TYPE_NIDRA_BHRUNSH => trans('laralum.nidra_bhrunsh'),
            self::TYPE_PRALAP => trans('laralum.pralap'),
            self::TYPE_BHRAM => trans('laralum.bhram'),
            self::TYPE_DEENTA => trans('laralum.deenta'),
        ];
    }

    public function getVatGrowthOption($key)
    {
        $list = $this->getVatGrowth();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getVatDecay()
    {
        return [
            self::TYPE_SAAD => trans('laralum.saad'),
            self::TYPE_ALP_BHASHAN => trans('laralum.alp_bhashan'),
            self::TYPE_SANGYA_MOH => trans('laralum.sangya_moh'),
            self::TYPE_KAPH_VRIDHI => trans('laralum.kaph_vridhi_janya_vyadhi'),
        ];
    }

    public function getVatDecayOption($key)
    {
        $list = $this->getVatDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getPittGrowth()
    {
        return [

            self::TYPE_PEET_VIT => trans('laralum.peet_vit'),
            self::TYPE_PEET_MUTRA => trans('laralum.peet_mutra'),
            self::TYPE_PEET_NETRA => trans('laralum.peet_netra'),
            self::TYPE_PEET_TWAK => trans('laralum.peet_twak'),
            self::TYPE_ATI_KSHUDA => trans('laralum.ati_kshuda'),
            self::TYPE_ATI_TRUSHNA => trans('laralum.ati_trushna'),
            self::TYPE_DAAH => trans('laralum.daah'),
            self::TYPE_ALP_NIDRA => trans('laralum.alp_nidra'),
        ];
    }

    public function getPittGrowthOption($key)
    {
        $list = $this->getPittGrowth();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getPittDecay()
    {
        return [
            self::TYPE_MAND_AGNI => trans('laralum.mand_agni'),
            self::TYPE_SHEET_PRATITI => trans('laralum.sheet_pratiti'),
            self::TYPE_PRABHA_HANI => trans('laralum.prabha_hani'),
        ];
    }

    public function getPittDecayOption($key)
    {
        $list = $this->getPittDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getKaphDoshDecayDecay()
    {
        return [
            self::TYPE_AGNI_SAAD => trans('laralum.agni_saad'),
            self::TYPE_PRASEK => trans('laralum.prasek'),
            self::TYPE_ALSYA => trans('laralum.alasya'),
            self::TYPE_GAURAV => trans('laralum.gaurav'),
            self::TYPE_KSHVATYA => trans('laralum.kshvatya'),
            self::TYPE_SHAITYA => trans('laralum.shaitya'),
            self::TYPE_ANG_SHISHITHILTA => trans('laralum.ang_shishithilta'),
            self::TYPE_KSHWAAS => trans('laralum.kshwas'),
            self::TYPE_KAAS => trans('laralum.alasya'),
            self::TYPE_ATI_NIDRA => trans('laralum.ati_nidra'),
        ];
    }

    public function getgetkaphdoshDecayDecayOption($key)
    {
        $list = $this->getKaphDoshDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }
    public function getKaphDoshecayDecay()
    {
        return [
            self::TYPE_KAPH_DOSH_DECAY_BHRAM => trans('laralum.bhram'),
            self::TYPE_KAPH_DOSH_DECAY_SHUNYATA => trans('laralum.shlesh_ashaye_shunyata'),
            self::TYPE_HRITDRAV => trans('laralum.hritdrav'),
            self::TYPE_SANDHI_SHITHILTA => trans('laralum.sandhi_shithilta'),
            self::TYPE_ANTRDAAH => trans('laralum.antardaah'),
            self::TYPE_JAAGRAN => trans('laralum.jaagran'),
        ];
    }

    public function getKaphDoshDecayOption($key)
    {
        $list = $this->getKaphDoshDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getRasGrowthDecay()
    {
        return [
            self::TYPE_AGNI_SAAD => trans('laralum.agni_saad'),
            self::TYPE_PRASEK => trans('laralum.prasek'),
            self::TYPE_ALSYA => trans('laralum.alasya'),
            self::TYPE_GAURAV => trans('laralum.gaurav'),
            self::TYPE_KSHVATYA => trans('laralum.kshvatya'),
            self::TYPE_SHAITYA => trans('laralum.shaitya'),
            self::TYPE_ANG_SHISHITHILTA => trans('laralum.ang_shishithilta'),
            self::TYPE_KSHWAAS => trans('laralum.kshwas'),
            self::TYPE_KAAS => trans('laralum.alasya'),
            self::TYPE_ATI_NIDRA => trans('laralum.ati_nidra'),
        ];
    }

    public function getRasGrowthOption($key)
    {
        $list = $this->getRasGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }
    public function getRasDecayDecay()
    {
        return [
            self::TYPE_ROKSH => trans('laralum.roksh'),
            self::TYPE_SARAM => trans('laralum.saram'),
            self::TYPE_SOSH => trans('laralum.sosh'),
            self::TYPE_GLANI => trans('laralum.glani'),
            self::TYPE_SHABD_ASAHISHUNTA => trans('laralum.shabd_asahishunta'),
        ];
    }

    public function getRasDecayOption($key)
    {
        $list = $this->getRasDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }


    public function getValue($attr)
    {
        if ($attr == 'vat_dosh_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getVatGrowthOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'vat_dosh_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getVatDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'pitt_dosh_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getPittGrowthOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'pitt_dosh_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getPittDecayOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'kaph_dosh_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getgetkaphdoshDecayDecayOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'kaph_dosh_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getKaphDoshDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'ras_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getRasGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'ras_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getRasDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        return 'No';
    }



}
