<?php

namespace App\Http\Controllers\Laralum;

use App\PhysiotherapyExerciseCategory;
use App\Users_Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PhysiotherapyExerciseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        Laralum::permissionToAccess('admin.permission_exercise_categories.list');
        $models = PhysiotherapyExerciseCategory::get();
        return view('laralum/physiotherpy_exercise_categories/index', ['categories' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.permission_exercise_categories.list');
        return view('laralum/physiotherpy_exercise_categories/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.permission_exercise_categories.list');
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('Laralum::physiotherpy_exercise_categories.index')->with('error', 'Something went wrong');
        }
        $category = new PhysiotherapyExerciseCategory();
        $category->title = Input::get('title');
        $category->created_by = Auth::user()->id;
        $category->save();
        return redirect()->route('Laralum::physiotherpy_exercise_categories.index')->with('success', 'Sucessfully Added');
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

        Laralum::permissionToAccess('admin.permission_exercise_categories.list');

        $category = PhysiotherapyExerciseCategory::find($id);
        return view('laralum/physiotherpy_exercise_categories/edit', ['category' => $category]);
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
        Laralum::permissionToAccess('admin.permission_exercise_categories.list');

        //
        $category = PhysiotherapyExerciseCategory::find($id);

        $inputs=$request->all();

        $category->update($inputs);

        return redirect()->route('Laralum::physiotherpy_exercise_categories.index')->with('success', 'Sucessfully Update');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */



    public function destroy($id)
    {
        //
        Laralum::permissionToAccess('admin.permission_exercise_categories.list');

        $category=PhysiotherapyExerciseCategory::find($id);
        $category->delete();
        return redirect()->back()->with('success', 'Sucessfully Deleted');

    }

    public function printPhysiotherpyExerciseCategorie()
    {

        $models = PhysiotherapyExerciseCategory::get();
        return view('laralum/physiotherpy_exercise_categories/print_physiotherpy_exercise_categories', [
            'categories' => $models,
            'print' => true

        ]);
    }
}
