<?php

namespace App\Http\Controllers\Laralum;

use App\ConsultationCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsultationChargeController extends Controller
{
    /**
     * consultation_charge listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');
        $models = ConsultationCharge::all();

        return view('laralum.consultation_charges.index', compact('models'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');
        $model = ConsultationCharge::find($id);

        # Return the view
        return view('laralum.consultation_charges.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');
        # Find the row
        $consultation_charge = ConsultationCharge::findOrFail($id);

        try {

            if ($consultation_charge->setData($request)) {
                $consultation_charge->save();
                return redirect()->route('Laralum::admin.consultation_charge')->with('success', 'ConsultationCharge edited successfully.');
            } else {
                return redirect()->route('Laralum::admin.consultation_charge')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the consultation_charge, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::consultation_charges')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add consultation_charge for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');
        $model = ConsultationCharge::first();
        if ($model == null){
            $model = new ConsultationCharge();
        }


        # Return the view
        return view('laralum.consultation_charges.create', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');
        $rules = ConsultationCharge::rules();

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $model = ConsultationCharge::first();
            if ($model == null){
                $model = new ConsultationCharge();
            }

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::admin.consultation_charges')->with('success', 'Consultation Charges Set successfully.');
            } else {
                return redirect()->route('Laralum::admin.consultation_charges')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the consultation_charge, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.consultation_charges')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.consultation_charges');

        # Select ConsultationCharge
        $consultation_charge = ConsultationCharge::findOrFail($id);

        $consultation_charge->delete();
        return redirect()->route('Laralum::admin.consultation_charge')->with('success', 'Successfully Deleted Consultation charge.');
    }
}
