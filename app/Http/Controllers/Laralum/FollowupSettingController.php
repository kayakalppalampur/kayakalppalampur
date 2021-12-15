<?php

namespace App\Http\Controllers\Laralum;

use App\FollowupSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FollowupSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $models = FollowupSetting::all();
        return view('laralum.followup_settings.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $model = new FollowupSetting();
        return view('laralum.followup_settings.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $model = new \App\FollowupSetting();

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model->setData($request);

        if($model->save()) {
            return redirect('/admin/followup-settings')->with('success', 'Successfully Added Popup');
        }

        return redirect()->back()->withInput()->with('error', 'Something went wrong');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $model = FollowupSetting::find($id);
        return view('laralum.followup_settings.view', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $model = FollowupSetting::find($id);
        return view('laralum.followup_settings.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $model = \App\FollowupSetting::find($id);

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model->setData($request);

        if($model->save()) {
            return redirect('/admin/followup-settings')->with('success', 'Successfully Updated Email Template');
        }

        return redirect()->back()->withInput()->with('error', 'Something went wrong');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $model = FollowupSetting::find($id);

        if ($model->delete()) {
            return redirect('admin/followup-settings')->with('success', 'Successfully Deleted Template');
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');
    }
}
