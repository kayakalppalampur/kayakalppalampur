<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApplyRecommendExcercise extends Model
{

	protected $table = 'apply_recommend_exercises';
	const STATE_ACTIVE = 1;
	const STATE_INACTIVE = 0;

	protected $fillable = [
        'booking_id',
        'doctor_id',
        'physiotherpy_exercise_id',
        'patient_id',
	];


	public function setData($request)
	{
		$this->booking_id = $request->get('booking_id');
		$this->patient_id = $request->get('patient_id');
		$this->physiotherpy_exercise_id = $request->get('id');
		$this->doctor_id = Auth::user()->id;
		$this->state_id = $request->get('state_id');
	}


	public function patient()
	{
		return $this->belongsTo('App\User', 'patient_id');
	}


	public function physiotherpy_exercise()
	{
		return $this->belongsTo('App\PhysiotherapyExercise', 'physiotherpy_exercise_id');
	}


	public function doctor()
	{
		return $this->belongsTo('App\User', 'doctor_id');
	}


	public function booking()
	{
		return $this->belongsTo('App\Booking', 'booking_id');
	}


}
