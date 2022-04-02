<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AyurvedDhatuExamination extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    //RAS GROWTH
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


    //RAS Decay
    const TYPE_ROKSH = 0;
    const TYPE_SARAM = 1;
    const TYPE_SOSH = 2;
    const TYPE_GLANI = 3;
    const TYPE_SHABD_ASAHISHUNTA = 4;


    //RAKHT GROWTH
    const TYPE_VISARP = 0;
    const TYPE_PLEEH = 1;
    const TYPE_VIDHRATHI = 2;
    const TYPE_KUSTH = 3;
    const TYPE_VAATRAKHT = 4;
    const TYPE_RAKHT_PITT = 5;
    const TYPE_GULM = 6;
    const TYPE_KAAMLA = 7;
    const TYPE_VYANG = 8;
    const TYPE_AGNI_NAASH = 9;
    const TYPE_SAMOOH = 10;
    const TYPE_RAKHT_TWAK = 11;
    const TYPE_RAKHT_NETRA = 12;
    const TYPE_RAKHT_MOTRA = 13;


    //RAKHT Decay
    const TYPE_AML_PRATI = 0;
    const TYPE_SHISHIR_PRATI = 1;
    const TYPE_SHEERA_SHETHILP = 2;
    const TYPE_ROOKHTHA = 3;


    //MAANS GROWTH
    const TYPE_GAND = 0;
    const TYPE_ABURD = 1;
    const TYPE_GRATHI = 2;
    const TYPE_UTHER_VRIDHI = 3;
    const TYPE_ADI_MAANS = 4;
    const TYPE_MED_VRIDHI = 5;
    const TYPE_SARAM_MAANS = 6;
    const TYPE_GAL_GAND = 7;


    //MAANS Decay
    const TYPE_GLANI_MAANS = 0;
    const TYPE_GAND_SHUSKTA = 1;
    const TYPE_SIFK_SHUSKTA = 2;
    const TYPE_SANDHI_VEDNA = 3;

    //MED GROWTH
    const TYPE_ALP_CHESHTHA_SHWAS = 0;
    const TYPE_SIFAK_LAMBAN = 1;
    const TYPE_STAN_LAMBAN = 2;
    const TYPE_UTHER_LAMBAN = 3;


    //MED Decay
    const TYPE_GLANI_MED = 0;
    const TYPE_GAND_SHUSKTA_MED = 1;
    const TYPE_SIFK_SHUSKTA_MED = 2;
    const TYPE_SANDHI_VEDNA_MED = 3;

    //ASTHI GROWTH
    const TYPE_ADHI_ASTHI = 0;
    const TYPE_ADHI_DANT = 1;

    //ASTHI Decay
    const TYPE_ASTHI_TOD = 0;
    const TYPE_DANT_SADAN = 1;
    const TYPE_KASH_SADAN = 2;
    const TYPE_NAKH_SADAN = 3;


    //MAJJA GROWTH
    const TYPE_NETRA_GAURAV = 0;
    const TYPE_ANG_GAURAV = 1;
    const TYPE_PURV_SATHULTA = 2;
    const TYPE_ARUNSHI = 3;

    //MAJJA Decay
    const TYPE_ASTHI_SAUSHIRYA = 0;
    const TYPE_BHERAM = 1;
    const TYPE_TIMIR_DERSHAN = 2;


    //SHUKRA GROWTH
    const TYPE_STRI_KAMTA_VRIDHI = 0;
    const TYPE_SHUKRA_VRIDHI = 1;
    const TYPE_SHUKRA_ASHMARI = 2;


    //SHUKRA Decay
    const TYPE_VRUSHN_TOD = 0;
    const TYPE_MEDAR_TOD = 1;
    const TYPE_GHUMTA = 2;

    protected $fillable = [
        'patient_id',
        'status',
        'ras_growth',
        'ras_decay',
        'rakht_growth',
        'rakht_decay',
        'maans_growth',
        'maans_decay',
        'med_growth',
        'med_decay',
        'asthi_growth',
        'asthi_decay',
        'majja_growth',
        'majja_decay',
        'shukra_growth',
        'shukra_decay',
        'rog_nidan',
        'vyadhi_ka_naam',
        'created_by',
        'booking_id'
    ];

    protected $table = 'ayurved_dhatu_examinations';

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

        $ras_growth = is_array($request->get('ras_growth')) ? implode(',', $request->get('ras_growth')) : '';
        $this->ras_growth = $ras_growth;
        $ras_decay = is_array($request->get('ras_decay')) ? implode(',', $request->get('ras_decay')) : '';
        $this->ras_decay = $ras_decay;

        $rakht_growth = is_array($request->get('rakht_growth')) ? implode(',', $request->get('rakht_growth')) : '';
        $this->rakht_growth = $rakht_growth;
        $rakht_decay = is_array($request->get('rakht_decay')) ? implode(',', $request->get('rakht_decay')) : '';
        $this->rakht_decay = $rakht_decay;

        $maans_growth = is_array($request->get('maans_growth')) ? implode(',', $request->get('maans_growth')) : '';
        $this->maans_growth = $maans_growth;
        $maans_decay = is_array($request->get('maans_decay')) ? implode(',', $request->get('maans_decay')) : '';
        $this->maans_decay = $maans_decay;

        $med_growth = is_array($request->get('med_growth')) ? implode(',', $request->get('med_growth')) : '';
        $this->med_growth = $med_growth;
        $med_decay = is_array($request->get('med_decay')) ? implode(',', $request->get('med_decay')) : '';
        $this->med_decay = $med_decay;

        $asthi_growth = is_array($request->get('asthi_growth')) ? implode(',', $request->get('asthi_growth')) : '';
        $this->asthi_growth = $asthi_growth;
        $asthi_decay = is_array($request->get('asthi_decay')) ? implode(',', $request->get('asthi_decay')) : '';
        $this->asthi_decay = $asthi_decay;

        $majja_growth = is_array($request->get('majja_growth')) ? implode(',', $request->get('majja_growth')) : '';
        $this->majja_growth = $majja_growth;
        $majja_decay = is_array($request->get('majja_decay')) ? implode(',', $request->get('majja_decay')) : '';
        $this->majja_decay = $majja_decay;

        $shukra_growth = is_array($request->get('shukra_growth')) ? implode(',', $request->get('shukra_growth')) : '';
        $this->shukra_growth = $shukra_growth;
        $shukra_decay = is_array($request->get('shukra_decay')) ? implode(',', $request->get('shukra_decay')) : '';
        $this->shukra_decay = $shukra_decay;

        $rog_nidan =  $request->get('rog_nidan');
        $this->rog_nidan = $rog_nidan;

        $vyadhi_ka_naam = $request->get('vydhi_ka_naam');
        $this->vyadhi_ka_naam = $vyadhi_ka_naam;

        return $this;
    }

    public function isChecked($attr, $val)
    {
	if ($this->$attr == ''){
		return false;
        }
        $values = explode(",", $this->$attr);

        if (in_array($val, $values)) {
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

    public static function customDeleteBooking($b_id)
    {
        $models = self::where('booking_id', $b_id)->get();
        foreach ($models as $model) {
            $model->delete();
        }
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

    public function getrakhtGrowthDecay()
    {
        return [
            self::TYPE_VISARP => trans('laralum.visarp'),
            self::TYPE_PLEEH => trans('laralum.pleeh'),
            self::TYPE_VIDHRATHI => trans('laralum.vidhrathi'),
            self::TYPE_KUSTH => trans('laralum.kusth'),
            self::TYPE_VAATRAKHT => trans('laralum.vaatrakht'),
            self::TYPE_RAKHT_PITT => trans('laralum.rakht_pitt'),
            self::TYPE_GULM => trans('laralum.gulm'),
            self::TYPE_KAAMLA => trans('laralum.kaamla'),
            self::TYPE_VYANG => trans('laralum.vyang'),
            self::TYPE_AGNI_NAASH => trans('laralum.agni_naash'),
            self::TYPE_SAMOOH => trans('laralum.samooh'),
            self::TYPE_RAKHT_TWAK => trans('laralum.rakht_twak'),
            self::TYPE_RAKHT_NETRA => trans('laralum.rakht_netra'),
            self::TYPE_RAKHT_MOTRA => trans('laralum.rakht_motra'),
        ];
    }

    public function getrakhtGrowthOption($key)
    {
        $list = $this->getrakhtGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getRakhtDecayDecay()
    {
        return [
            self::TYPE_AML_PRATI => trans('laralum.aml_prati'),
            self::TYPE_SHISHIR_PRATI => trans('laralum.shishir_prati'),
            self::TYPE_SHEERA_SHETHILP => trans('laralum.sheera_shethilp'),
            self::TYPE_ROOKHTHA => trans('laralum.rookhtha'),
        ];
    }

    public function getRakhtDecayOption($key)
    {
        $list = $this->getRakhtDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }


    public function getMaansGrowthDecay()
    {
        return [
            self::TYPE_GAND => trans('laralum.gand'),
            self::TYPE_ABURD => trans('laralum.shishir_prati'),
            self::TYPE_GRATHI => trans('laralum.grathi'),
            self::TYPE_UTHER_VRIDHI => trans('laralum.uther_vridhi'),
            self::TYPE_ADI_MAANS => trans('laralum.adi_maans'),
            self::TYPE_MED_VRIDHI => trans('laralum.med_vridhi'),
            self::TYPE_SARAM_MAANS => trans('laralum.saram'),
            self::TYPE_GAL_GAND => trans('laralum.gal_gand'),
        ];
    }

    public function getMaansGrowthOption($key)
    {
        $list = $this->getMaansGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }


    public function getMaansDecayDecay()
    {
        return [
            self::TYPE_GLANI_MAANS => trans('laralum.glani'),
            self::TYPE_GAND_SHUSKTA => trans('laralum.gand_shuskta'),
            self::TYPE_SIFK_SHUSKTA => trans('laralum.sifk_shuskta'),
            self::TYPE_SANDHI_VEDNA => trans('laralum.sandhi_vedna'),
        ];
    }

    public function getMaansDecayOption($key)
    {
        $list = $this->getMaansDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getMedGrowthDecay()
    {
        return [
            self::TYPE_ALP_CHESHTHA_SHWAS => trans('laralum.alp_cheshtha_shwas'),
            self::TYPE_SIFAK_LAMBAN => trans('laralum.sifak_lamban'),
            self::TYPE_STAN_LAMBAN => trans('laralum.stan_lamban'),
            self::TYPE_UTHER_LAMBAN => trans('laralum.uther_lamban'),
        ];
    }

    public function getMedGrowthOption($key)
    {
        $list = $this->getMedGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getMedDecayOptionDecay()
    {
        return [
            self::TYPE_GLANI_MED => trans('laralum.glani'),
            self::TYPE_GAND_SHUSKTA_MED => trans('laralum.gand_shuskta'),
            self::TYPE_SIFK_SHUSKTA_MED => trans('laralum.sifk_shuskta'),
            self::TYPE_SANDHI_VEDNA_MED => trans('laralum.sandhi_vedna'),
        ];
    }

    public function getMedDecayOption($key)
    {
        $list = $this->getMedDecayOptionDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getAsthiGrowthDecay()
    {
        return [
            self::TYPE_ADHI_ASTHI => trans('laralum.adhi_asthi'),
            self::TYPE_ADHI_DANT => trans('laralum.adhi_dant'),
        ];
    }

    public function getAsthiGrowthOption($key)
    {
        $list = $this->getAsthiGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getAsthiDecayDecay()
    {
        return [
            self::TYPE_ASTHI_TOD => trans('laralum.asthi_tod'),
            self::TYPE_DANT_SADAN => trans('laralum.dant_sadan'),
            self::TYPE_KASH_SADAN => trans('laralum.kash_sadan'),
            self::TYPE_NAKH_SADAN => trans('laralum.nakh_sadan'),
        ];
    }

    public function getAsthiDecayOption($key)
    {
        $list = $this->getAsthiDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }


    public function getMajjaGrowthDecay()
    {
        return [
            self::TYPE_NETRA_GAURAV => trans('laralum.netra_gaurav'),
            self::TYPE_ANG_GAURAV => trans('laralum.ang_gaurav'),
            self::TYPE_PURV_SATHULTA => trans('laralum.purv_sathulta'),
            self::TYPE_ARUNSHI => trans('laralum.arunshi'),
        ];
    }

    public function getMajjaGrowthOption($key)
    {
        $list = $this->getMajjaGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getMajjaDecayDecay()
    {
        return [
            self::TYPE_ASTHI_SAUSHIRYA => trans('laralum.asthi_saushirya'),
            self::TYPE_BHERAM => trans('laralum.bheram'),
            self::TYPE_TIMIR_DERSHAN => trans('laralum.timir_dershan'),
        ];
    }

    public function getMajjaDecayOption($key)
    {
        $list = $this->getMajjaDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getShukraGrowthDecay()
    {
        return [
            self::TYPE_STRI_KAMTA_VRIDHI => trans('laralum.stri_kamta_vridhi'),
            self::TYPE_SHUKRA_VRIDHI => trans('laralum.shukra_vridhi'),
            self::TYPE_SHUKRA_ASHMARI => trans('laralum.shukra_ashmari'),
        ];
    }

    public function getShukraGrowthOption($key)
    {
        $list = $this->getShukraGrowthDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getShukraDecayDecay()
    {
        return [
            self::TYPE_VRUSHN_TOD => trans('laralum.vrushn_tod'),
            self::TYPE_MEDAR_TOD => trans('laralum.medar_tod'),
            self::TYPE_GHUMTA => trans('laralum.ghumta'),
        ];
    }

    public function getShukraDecayOption($key)
    {
        $list = $this->getShukraDecayDecay();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }


    public function getValue($attr)
    {

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

        if ($attr == 'rakht_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getrakhtGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'rakht_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getRakhtDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'maans_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMaansGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'maans_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMaansDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'med_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMedGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'med_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMedDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'asthi_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getAsthiGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'asthi_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getAsthiDecayOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'majja_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMajjaGrowthOption($value);
            }

            return implode(',', $value_ar);
        }
        if ($attr == 'majja_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getMajjaDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'shukra_growth') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getShukraGrowthOption($value);
            }

            return implode(',', $value_ar);
        }


        if ($attr == 'shukra_decay') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getShukraDecayOption($value);
            }

            return implode(',', $value_ar);
        }

        return 'No';
    }

}
