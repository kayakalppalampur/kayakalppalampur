<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhysiotherapyExerciseCategory extends Model
{
    //
    protected $fillable = [
        'title',
        'created_by'

    ];

    public function createUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }


    public static function getRoutesArray()
    {
        return [
            'Laralum::physiotherpy_exercise_categories.index',
            'Laralum::physiotherpy_exercise_category_create',
            'Laralum::physiotherpy_exercise_category_show',
            'Laralum::physiotherpy_exercise_category_edit',
            'Laralum::physiotherpy_exercise_category_update',
            'Laralum::physiotherpy_exercise_category_delete'
        ];
    }

    public function setData($request)
    {
        $this->title = $request->get('title');
        $this->created_by = $request->get('created_by');
    }
}
