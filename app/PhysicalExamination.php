<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhysicalExamination extends Model
{
    //
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_DISCHARGED = 3;

    protected $fillable = [
        'patient_id',
        'status',
        'created_by',
        'hair',
        'forehead',
        'eyes',
        'booking_id',
        'built',
        'nourishment',
        'temperature',
        'respiratory_rate',
        'icterus',
        'cyanosis',
        'nails',
        'clubbing',
        'lymph_nodes_enlargement',
        'oedema',
        'tongue',
        'heart_rate',
        'anaemia'
    ];

    protected $table = 'physical_examinations';

    public function patient()
    {
        return $this->belongsTo("App\User", "patient_id");
    }

    public function setData($request)
    {
        $this->booking_id = $request->get("booking_id");
        $this->patient_id = $request->get("patient_id");
        $this->created_by = \Auth::user()->id;
        $this->status = self::STATUS_PENDING;
        $this->patient_id = $request->get("patient_id");
        $this->hair = $request->get('hair');
        $this->forehead = $request->get('forehead');
        $this->eyes = $request->get('eyes');
        $this->nails = $request->get('nails');
        $this->built = $request->get('built');
        $this->nourishment = $request->get('nourishment');
        $this->temperature = $request->get('temperature');
        $this->respiratory_rate = $request->get('respiratory_rate');
        $this->icterus = $request->get('icterus');
        $this->cyanosis = $request->get('cyanosis');
        $this->clubbing = $request->get('clubbing');
        $this->lymph_nodes_enlargement = $request->get('lymph_nodes_enlargement');
        $this->oedema = $request->get('oedema');
        $this->tongue = $request->get('tongue');
        $this->heart_rate = $request->get('heart_rate');
        $this->anaemia = $request->get('anaemia');
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

}
