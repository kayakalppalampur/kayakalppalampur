<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AyurvedAturExamination extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    const TYPE_VAT = 0;
    const TYPE_PITT = 1;
    const TYPE_KAPH = 2;
    const TYPE_VATPITT = 3;
    const TYPE_PITTKAPH = 4;
    const TYPE_KAPHVAT = 5;
    const TYPE_SAM = 6;

    const TYPE_RAKT = 0;
    const TYPE_RAS = 1;
    const TYPE_MANS = 2;
    const TYPE_MED = 3;
    const TYPE_ASTHI = 4;
    const TYPE_MAJJ = 5;
    const TYPE_SHUKRA = 6;
    const TYPE_PRAVER = 0;

    const TYPE_UTTAM = 0;
    const TYPE_MADHYAM = 1;
    const TYPE_HEEN = 2;
    const TYPE_VRIDH = 3;

    const TYPE_BAAL = 0;
    const TYPE_YUVA = 1;
    const TYPE_PRAUN = 2;
    const TYPE_JEERNYA = 3;

    const TYPE_ANOOP = 0;
    const TYPE_JANGAL = 1;
    const TYPE_SADHARAN = 2;

    const TYPE_ADAAN = 0;
    const TYPE_VISARG = 1;
    const TYPE_SHISHIR = 2;
    const TYPE_VARSHA = 3;
    const TYPE_VASANT = 4;
    const TYPE_SHARAD = 5;
    const TYPE_GREESH = 6;
    const TYPE_HEMANT = 7;


    protected $fillable = [
        'patient_id',
        'status',
        'prakriti',
        'prakriti_comment',
        'saar',
        'saar_comment',
        'sanhanan',
        'praman',
        'satmyaya',
        'satva',
        'ahaar_shakti',
        'vyayaam_shakti',
        'vaya',
        'varsh',
        'bal',
        'drishya',
        'uttpatti_desh',
        'vyadhit_desh',
        'chikitsa_desh',
        'kaal',
        'anal',
        'rogi_awastha',
        'rog_awastha',
        'created_by',
        'booking_id'
    ];

    protected $table = 'ayurved_atur_examinations';

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
        $this->prakriti = $request->get('prakriti');
        $this->sanhanan = $request->get('sanhanan');

        $saar = is_array($request->get('saar')) ? implode(',', $request->get('saar')) : '';
        $this->saar = $saar;
        //$this->saar = $request->get('saar');
        $this->praman = $request->get('praman');
        $this->satmyaya = $request->get('satmyaya');
        $this->satva = $request->get('satva');
        $this->ahaar_shakti = $request->get('ahaar_shakti');
        $this->vyayaam_shakti = $request->get('vyayaam_shakti');
        $this->vaya = $request->get('vaya');
        $this->bal = $request->get('bal');
        $this->varsh = $request->get('varsh');
        $this->drishya = $request->get('drishya');
        $this->uttpatti_desh = $request->get('utpatti_desh');
        $this->vyadhit_desh = $request->get('vyadhit_desh');
        $this->chikitsa_desh = $request->get('chikitsa_desh');
        $kaal = is_array($request->get('kaal')) ? $request->get('kaal') : array($request->get('kaal'));

        $kaal = implode(',', $kaal);
        $this->kaal = $kaal;
        $this->anal = $request->get('anal');
        $this->rogi_awastha = $request->get('rogi_awastha');
        $this->rog_awastha = $request->get('rog_awastha');
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

    public function getPrakritiType($id = null)
    {
        $list = [
            self::TYPE_VAT => trans('laralum.vat'),
            self::TYPE_PITT => trans('laralum.pitt'),
            self::TYPE_KAPH => trans('laralum.kaph'),
            self::TYPE_VATPITT => trans('laralum.vaatpitt'),
            self::TYPE_PITTKAPH => trans('laralum.pittkaph'),
            self::TYPE_KAPHVAT => trans('laralum.kaphvitt'),
            self::TYPE_SAM => trans('laralum.sam'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getSanhananType($id = null)
    {
        $list = [
            self::TYPE_UTTAM => trans('laralum.uttam'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getSatmyayaType($id = null)
    {
        $list = [
            self::TYPE_PRAVER => trans('laralum.pravar'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getSatvaType($id = null)
    {
        $list = [
            self::TYPE_PRAVER => trans('laralum.pravar'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getAhaarshaktiType($id = null)
    {
        $list = [
            self::TYPE_UTTAM => trans('laralum.uttam'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getVyayaamshaktiType($id = null)
    {
        $list = [
            self::TYPE_PRAVER => trans('laralum.pravar'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getVayaType($id = null)
    {
        $list = [
            self::TYPE_BAAL => trans('laralum.baal'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_VRIDH => trans('laralum.vridh'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getBalType($id = null)
    {
        $list = [
            self::TYPE_PRAVER => trans('laralum.pravar'),
            self::TYPE_MADHYAM => trans('laralum.madhyam'),
            self::TYPE_HEEN => trans('laralum.heen'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getUttpattiDeshType($id = null)
    {
        $list = [
            self::TYPE_ANOOP => trans('laralum.aanoop'),
            self::TYPE_JANGAL => trans('laralum.jangal'),
            self::TYPE_SADHARAN => trans('laralum.sadharan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getVyadhitDeshType($id = null)
    {
        $list = [
            self::TYPE_ANOOP => trans('laralum.aanoop'),
            self::TYPE_JANGAL => trans('laralum.jangal'),
            self::TYPE_SADHARAN => trans('laralum.sadharan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }
    public function getChikitsaDeshType($id = null)
    {
        $list = [
            self::TYPE_ANOOP => trans('laralum.aanoop'),
            self::TYPE_JANGAL => trans('laralum.jangal'),
            self::TYPE_SADHARAN => trans('laralum.sadharan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getAnalType($id = null)
    {
        $list = [
            self::TYPE_UTTAM => trans('laralum.sam'),
            self::TYPE_MADHYAM => trans('laralum.visham'),
            self::TYPE_JEERNYA => trans('laralum.teekshan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getKaalType($id = null)
    {
        $list = [
            self::TYPE_SHISHIR => trans('laralum.shishir'),
            self::TYPE_VASANT => trans('laralum.vasant'),
            self::TYPE_GREESH => trans('laralum.greeshm'),
            self::TYPE_VARSHA => trans('laralum.varsha'),
            self::TYPE_SHARAD => trans('laralum.sharad'),
            self::TYPE_HEMANT => trans('laralum.hemant'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getRogiAwasthaType($id = null)
    {
        $list = [
            self::TYPE_UTTAM => trans('laralum.sam'),
            self::TYPE_MADHYAM => trans('laralum.visham'),
            self::TYPE_JEERNYA => trans('laralum.teekshan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getRogAwasthaType($id = null)
    {
        $list = [
            self::TYPE_UTTAM => trans('laralum.sam'),
            self::TYPE_MADHYAM => trans('laralum.visham'),
            self::TYPE_JEERNYA => trans('laralum.teekshan'),

        ];

        if ($id === null) {
            return $list;
        }

        if (isset($list[$id])) {
            return $list[$id];
        }

        return $id;
    }

    public function getSaar()
    {
        return [
            self::TYPE_RAKT => trans('laralum.rakt'),
            self::TYPE_RAS => trans('laralum.ras'),
            self::TYPE_MANS => trans('laralum.maans'),
            self::TYPE_MED => trans('laralum.med'),
            self::TYPE_ASTHI => trans('laralum.asthi'),
            self::TYPE_MAJJ => trans('laralum.majj'),
            self::TYPE_SHUKRA => trans('laralum.shukra'),
        ];
    }

    public function getSaarOption($key)
    {
        $list = $this->getSaar();
        $value = isset($list[$key]) ? $list[$key] : '';
        return $value;
    }

    public function getValue($attr)
    {
        if ($this->$attr === null ) {
            return "";
        }

        if ($attr == 'saar') {
            $values = explode(',', $this->$attr);
            $value_ar = [];

            foreach ($values as $value) {
                $value_ar[] = $this->getSaarOption($value);
            }

            return implode(',', $value_ar);
        }

        if ($attr == 'prakriti') {
            return $this->getPrakritiType($this->$attr);
        }

        if ($attr == 'sanhanan') {
            return $this->getSanhananType($this->$attr);
        }

        if ($attr == 'satmyaya') {
            return $this->getSatmyayaType($this->$attr);
        }
        if ($attr == 'satva') {
            return $this->getSatvaType($this->$attr);
        }
        if ($attr == 'ahaar_shakti') {
            return $this->getAhaarshaktiType($this->$attr);
        }
        if ($attr == 'vyayaam_shakti') {
            return $this->getVyayaamshaktiType($this->$attr);
        }
        if ($attr == 'vaya') {
            return $this->getVayaType($this->$attr);
        }
        if ($attr == 'bal') {
            return $this->getBalType($this->$attr);
        }
        if ($attr == 'uttpatti_desh') {
            return $this->getUttpattiDeshType($this->$attr);
        }
        if ($attr == 'vyadhit_desh') {
            return $this->getVyadhitDeshType($this->$attr);
        }
        if ($attr == 'chikitsa_desh') {
            return $this->getChikitsaDeshType($this->$attr);
        }
        if ($attr == 'kaal') {
            return $this->getKaalType($this->$attr);
        }
        if ($attr == 'anal') {
            return $this->getAnalType($this->$attr);
        }
        if ($attr == 'rogi_awastha') {
            return $this->getRogiAwasthaType($this->$attr);
        }
        if ($attr == 'rog_awastha') {
            return $this->getRogAwasthaType($this->$attr);
        }
        return $this->$attr;

    }

}

