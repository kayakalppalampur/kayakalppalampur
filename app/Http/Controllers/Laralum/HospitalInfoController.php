<?php

namespace App\Http\Controllers\Laralum;

use App\HospitalInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HospitalInfoController extends Controller
{
    /**
     * hospital_info listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');
        $model = HospitalInfo::first();
        if ($model == null)
            $model = new HospitalInfo();
        return view('laralum.hospital_info.create', compact('model'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');
        $model = HospitalInfo::find($id);

        # Return the view
        return view('laralum.hospital_info.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');
        # Find the row
        $hospital_info = HospitalInfo::findOrFail($id);

        try {

            if ($hospital_info->setData($request)) {
                $hospital_info->save();
                return redirect()->route('Laralum::admin.hospital_info')->with('success', 'HospitalInfo edited successfully.');
            } else {
                return redirect()->route('Laralum::admin.hospital_info')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the hospital_info, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::hospital_infos')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add hospital_info for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');
        $model = HospitalInfo::first();
        if ($model == null)
            $model = new HospitalInfo();

        # Return the view
        return view('laralum.hospital_info.create', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');
        $rules = HospitalInfo::rules();

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $model = HospitalInfo::first();
            if ($model == null)
                $model = new HospitalInfo();

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::admin.hospital_info')->with('success', 'Hospital Info added successfully.');
            } else {
                return redirect()->route('Laralum::admin.hospital_info')->withInput()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the hospital_info, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::hospital_infos')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.hospital_info');

        # Select HospitalInfo
        $hospital_info = HospitalInfo::findOrFail($id);

        $hospital_info->delete();
        return redirect()->route('Laralum::admin.hospital_info')->with('success', 'Successfully Deleted Hospital Info.');
    }

}
