<?php

namespace App\Http\Controllers\Laralum;

use App\PhysiotherpyExcercise;
use App\PhysiotherapyExercise;
use App\PhysiotherapyExerciseCategory;
use App\SystemFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class PhysiotherapyExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');
        $models = PhysiotherapyExercise::get();
        return view('laralum/physiotherpy_exercises/index', ['exercises' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getListOfCategory()
    {


        $list = [];

        $categories = PhysiotherapyExerciseCategory::all();
        if (!empty($categories)) {
            foreach ($categories as $cat) {

                $list[$cat->id] = $cat->title;

            }
        }

        return $list;


    }

    public function create()
    {
        //
        $list = [];

        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');

        $categories = PhysiotherapyExerciseCategory::all();
        if (!empty($categories)) {
            foreach ($categories as $cat) {

                $list[$cat->id] = $cat->title;

            }
        }

        return view('laralum/physiotherpy_exercises/create', ['category' => $list]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');

        $validator = Validator::make($request->all(), [
            'name_of_excercise' => 'required',
            'description' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->route('Laralum::physiotherpy_exercises.index')->with('error', 'Something went wrong');
        }
        $model = new PhysiotherapyExercise();
        $model->setData($request);


        if ($model->save()) {
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    SystemFile::saveUploadedFile($image, $model);
                }

            }

        }


        return redirect()->route('Laralum::physiotherpy_exercises.index')->with('success', 'Excercise Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');

        //

        $exercise = PhysiotherapyExercise::find($id);

        $list = [];

        $list = $this->getListOfCategory();


        return view('laralum/physiotherpy_exercises/edit', ['exercise' => $exercise, 'list' => $list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');


        $exercise = PhysiotherapyExercise::find($id);
        $validator = Validator::make($request->all(), [
            'name_of_excercise' => 'required',
            'description' => 'required',

        ]);
        if ($validator->fails()) {
            return redirect()->route('Laralum::physiotherpy_exercises.index')->with('error', 'Something went wrong');
        }


        if ($exercise->update($request->all())) {
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    SystemFile::saveUploadedFile($image, $exercise);
                }

            }
        }

        return redirect()->route('Laralum::physiotherpy_exercises.index')->with('success', 'Excercise Updated Successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */


    public function destroy($id)
    {

        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');

        $model = PhysiotherapyExercise::find($id);
        $model->delete();
        return redirect()->back()->with('success', 'Sucessfully Deleted');
    }


    public function deleteSystemFile(Request $request)
    {

        Laralum::permissionToAccess('admin.physiotherpy_exercises.index');
        $model = SystemFile::find($request->get('id'));
        if (!empty($model)) {
            $model->delete();
            $data['status'] = "OK";
        } else {
            $data['status'] = 'NOK';
        }

        return $data;
    }

    public function printPhysiotherpyExercises()
    {
        $models = PhysiotherapyExercise::get();
        # Return the view
        return view('laralum/physiotherpy_exercises/print_physiotherpy_exercises', [
            'exercises' => $models,
            'print' => true

        ]);
    }


}
