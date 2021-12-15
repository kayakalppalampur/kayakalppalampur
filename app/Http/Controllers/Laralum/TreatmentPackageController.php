<?php

namespace App\Http\Controllers\Laralum;

use App\TreatmentPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TreatmentPackageController extends Controller
{
    /**
     * treatment listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('treatments');
        $models = TreatmentPackage::select('*');
        $search = false;
        $option_ar = [];
        if ($request->get('filter_package_name')) {
            $search = true;
            $option_ar[] = "Package Name";
            $models = $models->where('package_name', 'LIKE', '%'.$request->get('filter_package_name')."%");
        }
        if ($request->get('filter_department_id')) {
            if ($request->get('filter_department_id') != "All") {
                $search = true;
                $option_ar[] = "Department";
                $models = $models->where('department_id', $request->get('filter_department_id'));
            }
        }

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";


        $count = $models->count();
        if($pagination == true) {
            $models = $models->paginate($per_page);
        }else{
            $models = $models->get();
        }

        return view('laralum.treatment_packages.index', compact('models', 'count', 'error', 'search'));
    }

    public function printTreatments()
    {
        $treatment_packages = TreatmentPackage::select('*');
        $treatment_packages = $treatment_packages->get();
        $print = true;
        return view('laralum.treatment_packages.print-treatments',compact('treatment_packages', 'print'));
    }

    /**
     * treatment details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('treatments');
        $treatment_package = TreatmentPackage::find($id);

        return view('laralum.treatment_packages.view',compact('treatment_package'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('treatments');

        # Find the treatment
        $model = TreatmentPackage::findOrFail($id);
        $selected_treatments = $model->getTreatmentsList(true);

        return view('laralum.treatment_packages.edit', [
            'model'       =>  $model,
            'selected_treatments' => $selected_treatments
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('treatments');

        # Find the row
        $model = TreatmentPackage::findOrFail($id);
        $rules = TreatmentPackage::rules();
        $validator = \Validator::make($request->all(), $rules);
        /*echo '<pre>'; print_r($request->all());exit;*/
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }
        try {

            if ($model->setData($request)) {
                $model->save();
                $model->saveTreatments($request->get('treatment_id'));
                return redirect()->route('Laralum::treatment_packages')->with('success', 'Treatment package edited successfully.');
            }else{
                return redirect()->route('Laralum::treatment_packages')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the treatment package, possible causes: ".$e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::treatment_packages')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add treatment for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('treatments');

        $model = new TreatmentPackage();
        return view('laralum.treatment_packages.create',
            [
                'model' => $model,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('treatments');
        $rules = TreatmentPackage::rules();
        $validator = \Validator::make($request->all(), $rules);
        /*echo '<pre>'; print_r($request->all());exit;*/
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $model = TreatmentPackage::where('package_name', $request->get("package_name"))->first();
            if ($model == null)
                $model = new TreatmentPackage();

            if ($model->setData($request)) {
                $model->save();
                $model->saveTreatments($request->get('treatment_id'));
                return redirect()->route('Laralum::treatment_packages')->with('success', 'Treatment Package added successfully.');
            }else{
                return redirect()->route('Laralum::treatment_packages')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the treatment, possible causes: ".$e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::treatments')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('treatments');

        # Select Treatment
        $treatment = TreatmentPackage::findOrFail($id);
        # Delete Treatment
        $treatment->delete();
        # Redirect the admin
        return redirect()->route('Laralum::treatment_packages')->with('success', "Package Deleted Successfully");
    }

    public function getDuration(Request $request)
    {
        $ids = explode(",",$request->get("ids"));
        return Treatment::getTotalDuration($ids);
    }

    public function treatmentTokens(Request $request)
    {
        $matchThese =   [];
        $search = false;
        $option_ar = [];
        $treatment_date = date("Y-m-d");
        if ($request->has('filter_patient_id') && $request->get('filter_patient_id') != ""){
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('filter_patient_id');
        }
        if ($request->has('filter_first_name') && $request->get('filter_first_name') != ""){
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('filter_first_name');
        }

        if ($request->has('filter_last_name') && $request->get('filter_last_name') != ""){
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != ""){
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }

        $filter_email = "";

        if ($request->has('filter_email')){
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }

        if ($request->has('filter_treatment_date')){
            $search = true;
            $treatment_date = $request->get('filter_treatment_date');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            $treatments = TreatmentToken::select('treatment_tokens.*')->join('users', 'users.id', '=', 'treatment_tokens.patient_id')->join('user_profiles', 'user_profiles.user_id', '=', 'treatment_tokens.patient_id')->join('patient_treatments', 'patient_treatments.treatment_token_id', '=', 'treatment_tokens.id')->where('patient_treatments.status', '!=', PatientTreatment::STATUS_DISCHARGED)->where(function($query) use ($matchThese,$filter_email) {
                foreach($matchThese as $key=>$match){
                    $query->where('user_profiles.'.$key,'like',"%$match%");
                }
                if($filter_email != "") {
                    $query->where('users.email', 'like', "%$filter_email%");
                }
            })->where('treatment_tokens.treatment_date',(string)$request->get('filter_treatment_date'))->orderBy('treatment_tokens.id', 'ASC');


            if ($pagination == true) {
                $count = $treatments->count();
                $treatments = $treatments->paginate($per_page);
            }else{
                $count = $treatments->count();
                $treatments = $treatments->get();
            }

        }else{
            $treatments = TreatmentToken::select('treatment_tokens.*')->join('users', 'users.id', '=', 'treatment_tokens.patient_id')/*->where('users.is_discharged', User::ADMIT)*/->where('treatment_tokens.treatment_date', (string) date("Y-m-d"))->orderBy('treatment_tokens.id', 'ASC');

            /*$treatments = PatientTreatment::select('patient_treatments.*')->where('patient_treatments.status', '!=', PatientTreatment::STATUS_DISCHARGED)->join('treatment_tokens', 'treatment_tokens.id', '=', 'patient_treatments.treatment_token_id')->where('treatment_tokens.treatment_date', (string) date("Y-m-d"))->orderBy('treatment_tokens.id', 'ASC');*/

            $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
            $pagination = true;
            if ($per_page == "All") {
                $pagination = false;
            }
            $count = $treatments->count();
            if ($pagination == true) {
                $count = $treatments->count();
                $treatments = $treatments->paginate($per_page);
            }else{
                $count = $treatments->count();
                $treatments = $treatments->get();
            }
        }

        return view('laralum.treatment.tokens',compact('treatments', 'count', 'error', 'treatment_date', 'search'));
    }


    public function updatePatientTreatment(Request $request, $id)
    {
        $treatment = PatientTreatment::find($id);

        if ($treatment != null) {
            $token = $treatment->treatmentToken;
            if ($token != null) {
                if ($token->status != TreatmentToken::STATUS_DISCHARGED) {
                    if ($request->get("status") !== null) {
                        $treatment->update([
                            'status' => $request->get("status"),
                            'not_attended_reason' => $request->get('not_attended_reason'),
                            'reason_submitted_by' => \Auth::user()->id
                        ]);

                        if ($request->get("status") == PatientTreatment::STATUS_COMPLETED) {
                            $token->update([
                                'status' => TreatmentToken::STATUS_ATTENDED
                            ]);
                        } else {
                            $treatments = $token->treatments;
                            $ok = false;
                            if ($treatments->count() > 0) {
                                foreach ($treatments as $treatment) {
                                    if ($treatment->status == PatientTreatment::STATUS_COMPLETED) {
                                        $ok = true;
                                    }
                                }
                            }
                            if ($ok == false) {
                                $token->update([
                                    'status' => TreatmentToken::STATUS_PENDING
                                ]);
                            }
                        }
                    }

                    $treatment->save();
                }
            }
        }
        return ['id' =>$id , 'status' => $treatment->getStatusOptions($treatment->status), 'reason' => $treatment->not_attended_reason];
    }


    public function export(Request $request, $type)
    {
        $treatments = Treatment::select('*')->orderBy('created_at', "DESC");
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $treatments->count();

        if ($pagination == true) {
            $treatments = $treatments->paginate($per_page);
        }else{
            $treatments = $treatments->get();
        }
        $treatments_array[] = [
            'Title', 'Duration', 'Price', 'Department'
        ];
        foreach ($treatments as $treatment) {
            $treatments_array[] = [
                $treatment->title,
                $treatment->getDuration(),
                $treatment->price,
                $treatment->department->title
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('treatments', function($excel) use ($treatments_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Treatments');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Treatments File');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function($sheet) use ($treatments_array) {
                $sheet->fromArray($treatments_array, null, 'A1', false, false);
            });
        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        }elseif($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        }else{
            $excel->download('pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
