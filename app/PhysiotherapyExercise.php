<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PhysiotherapyExercise extends Model
{
    //


    protected $fillable = [
        'category_id',
        'name_of_excercise',
        'created_by',
        'description'

    ];

    public function createUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }


    public function getCategory()
    {
      $model=PhysiotherapyExerciseCategory::where('id',$this->category_id)->first();
     return $model;

    }

    public function setData($request)
    {
        $this->category_id = $request->get('category_id');
        $this->description = $request->get('description');
        $this->name_of_excercise = $request->get('name_of_excercise');
        $this->created_by = Auth::user()->id;
   }




    public static function getRoutesArray()
    {
        return [
            'Laralum::physiotherpy_exercises.index',
            'Laralum::physiotherpy_exercise_create',
            'Laralum::physiotherpy_exercise_show',
            'Laralum::physiotherpy_exercise_edit',
            'Laralum::physiotherpy_exercise_update',
            'Laralum::physiotherpy_exercise_delete'
        ];
    }



}
