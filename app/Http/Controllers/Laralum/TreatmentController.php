<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Http\Controllers\Controller;
use App\PatientTreatment;
use App\Settings;
use App\Treatment;
use App\TreatmentToken;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Monolog\Handler\IFTTTHandler;

class TreatmentController extends Controller
{
    //
    /**
     * treatment listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        $treatments = Treatment::select('*')->orderBy('created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $treatments->count();
        if ($pagination == true) {
            $treatments = $treatments->paginate($per_page);
        } else {
            $treatments = $treatments->get();
        }

        return view('laralum.treatment.index', compact('treatments', 'count'));
    }

    public function printTreatments(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        $treatments = Treatment::select('*')->orderBy('created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $treatments->count();
        if ($pagination == true) {
            $treatments = $treatments->paginate($per_page);
        } else {
            $treatments = $treatments->get();
        }
        $print = true;
        return view('laralum.treatment.print-treatments', compact('treatments', 'print'));

    }

    /**
     * treatment details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        $treatment = Treatment::find($id);

        return view('laralum.treatment.view', compact('treatment'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.treatments');

        # Find the treatment
        $row = Treatment::findOrFail($id);
        \Session::put('treatment_id', $id);

        # Get all the data
        $data_index = 'treatments';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/treatment/edit', [
            'row' => $row,
            'fields' => $fields,
            'confirmed' => $confirmed,
            'empty' => $empty,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.treatments');

        # Find the row
        $treatment = Treatment::findOrFail($id);

        try {

            if ($treatment->setData($request)) {
                $treatment->save();
                return redirect()->route('Laralum::treatments')->with('success', 'Treatment edited successfully.');
            } else {
                return redirect()->route('Laralum::treatments')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the treatment, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::treatments')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add treatment for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        # Get all the data
        $data_index = 'treatments';
        require('Data/Create/Get.php');
        $model = new Treatment();
        return view('laralum.treatment.create',
            [
                'row' => $model,
                'fields' => $fields,
                'confirmed' => $confirmed,
                'encrypted' => $encrypted,
                'hashed' => $hashed,
                'masked' => $masked,
                'table' => $table,
                'code' => $code,
                'wysiwyg' => $wysiwyg,
                'relations' => $relations,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        $rules = Treatment::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

       // try {
            $treatment = Treatment::where('title', $request->get("title"))->first();
            if ($treatment == null)
                $treatment = new Treatment();

            if ($treatment->setData($request)) {
                $treatment->save();

                return redirect()->route('Laralum::treatments')->with('success', 'Treatment added successfully.');
            } else {
                return redirect()->route('Laralum::treatments')->with('error', 'Something went wrong. Please try again later.');
            }

        /*} catch (\Exception $e) {

            \Log::error("Failed to add the treatment, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::treatments')->with('error', 'Something went wrong. Please try again later.');
        }*/

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.treatments');

        # Select Treatment
        $treatment = Treatment::findOrFail($id);
        # Delete Treatment
        $treatment->delete();
        # Redirect the admin
        return redirect()->route('Laralum::treatments')->with('success', trans('laralum.msg_treatment_deleted'));

    }

    public function getDuration(Request $request)
    {
        $ids = explode(",", $request->get("ids"));
        return Treatment::getTotalDuration($ids);
    }

    public function treatmentTokens(Request $request)
    {
        Laralum::permissionToAccess('account.management');
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $matchTheseN = [];
        $match = [];
       // $treatment_date = date("Y-m-d");


        if ($request->has('id') && $request->get('id') != "") {
            $option_ar[] = "Id";
            $search = true;
            $matchTheseN['id'] = $request->get('id');
        }
        if ($request->has('kid') && $request->get('kid') != "") {
            $option_ar[] = "Patient Id";
            $search = true;
            $matchThese['kid'] = $request->get('kid');
        }

        /*if ($request->has('first_name') && $request->get('first_name') != "") {
            $option_ar[] = "First Name";
            $search = true;
            $matchThese['first_name'] = $request->get('first_name');
        }*/

        $filter_name = '';
        if (!empty($request->get('first_name'))) {
            $option_ar[] = "Name";
            $search = true;
            $filter_name = $request->first_name;
            $array = explode(' ', $filter_name);
            $matchThese['first_name'] = $array[0];
            $matchThese['last_name'] = '';

            if (isset($array[1])) {
                $matchThese['last_name'] = $array[1];
            }
        }

        /*if ($request->has('filter_last_name') && $request->get('filter_last_name') != "") {
            $option_ar[] = "Last Name";
            $search = true;
            $matchThese['last_name'] = $request->get('filter_last_name');
        }*/
        if ($request->has('filter_mobile') && $request->get('filter_mobile') != "") {
            $option_ar[] = "Mobile";
            $search = true;
            $matchThese['mobile'] = $request->get('filter_mobile');
        }
        $selected_treatments = "";
        if ($request->has('treatments') && $request->get('treatments') != "") {
            $option_ar[] = "Treatments";
            $search = true;
            $selected_treatments = $request->get('treatments');
            $match['treatments'] = $request->get('treatments');
        }

        $filter_email = "";

        if ($request->has('filter_email')) {
            $option_ar[] = "Email";
            $search = true;
            $filter_email = $request->get('filter_email');
        }

        if ($request->has('treatment_date')) {
		$option_ar[] = "Treatment Date";
            $search = true;
	      $formattedDate  = Settings::getFormattedDate($request->treatment_date);
           $matchTheseN['treatment_date'] = $formattedDate;
//date_format(date_create($request->get('treatment_date')),'Y-m-d');
        }
        $created_by = "";
        if ($request->has('created_by')) {
            $search = true;
$option_ar[] = "Created By";
            $created_by = $request->get('created_by');
            $match['created_by'] = $request->get('created_by');
        }

        $department_id = "";
        if ($request->has('department_id')) {
$option_ar[] = "Department";
            $search = true;
            $department_id = $request->get('department_id');
            $match['department_id'] = $request->get('department_id');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($search == true) {
            //return "here";
//            $array['treatment_date'] = date("Y-m-d");
            $treatments = TreatmentToken::select('treatment_tokens.*')
->leftjoin('bookings', 'bookings.id', '=', 'treatment_tokens.booking_id')
->leftjoin('user_profiles', 'user_profiles.id', '=', 'bookings.profile_id')
/*->leftjoin('patient_treatments', 'patient_treatments.treatment_token_id', '=', 'treatment_tokens.id')
->where('patient_treatments.status', '!=', PatientTreatment::STATUS_DISCHARGED)*/;
            if (!empty($matchThese) || !empty($matchTheseN)) {
                $treatments = $treatments->where(function ($query) use ($filter_name, $matchThese, $matchTheseN) {
                    foreach ($matchTheseN as $key => $match) {
                        $query->where('treatment_tokens.' . $key, 'like', "%$match%");
                    }
                    foreach ($matchThese as $key => $match) {
                        $query->where('user_profiles.' . $key, 'like', "%$match%");
                    }
                    /*if ($filter_name != "") {
                        $query->WhereRaw("concat(user_profiles.first_name, ' ', user_profiles.last_name) like '%$filter_name%' ");
                    }*/

                });
            }
//print_r($selected_treatments);exit;
if (!empty($selected_treatments)) {
 $treatments = $treatments->leftjoin('patient_treatments', 'patient_treatments.treatment_token_id', '=', 'treatment_tokens.id')->whereHas('treatments', function ($query) use ($selected_treatments){
	$query->where('treatment_id', $selected_treatments);
});
}

            if ($created_by) {
                $treatments = $treatments->where([
                    'treatment_tokens.created_by' => $created_by
                ]);
            }
            if ($department_id) {
                $treatments = $treatments->where([
                    'treatment_tokens.department_id' => $department_id
                ]);
            }
            if ($selected_treatments) {
                $treatments = $treatments->where('patient_treatments.treatment_id', $selected_treatments);
            }

            $treatments = $treatments->orderBy('treatment_tokens.id', 'ASC')->distinct();

            $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
            $pagination = true;

            if ($per_page == "All") {
                $pagination = false;
            }

            if ($pagination == true) {
                $count = $treatments->count();
                $treatments = $treatments->paginate($per_page);
            } else {
                $count = $treatments->count();
                $treatments = $treatments->get();
            }
if ($count > 0){ $error='';};
        } else {
            $array['treatment_date'] = date("Y-m-d");
            $matchTheseN['treatment_date'] = date("Y-m-d");
            $treatments = TreatmentToken::select('treatment_tokens.*')
                ->where('treatment_tokens.treatment_date', (string)date("Y-m-d"))->orderBy('treatment_tokens.id', 'ASC');

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
            } else {
                $count = $treatments->count();
                $treatments = $treatments->get();
            }
        }
        $matchThese['name'] = $request->first_name;

 $matchTheseN['treatment_date'] = $request->treatment_date;
        if ($request->ajax()) {

            $array = array_merge($matchThese, $matchTheseN, $match);
            return [
                'html' => view('laralum.treatment._token_list', ['treatments' => $treatments, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $array])->render()
            ];
        }
//echo '<pre>';
//print_r($treatments);exit;
        $matchThese['name'] = $request->get('first_name');
        $array = array_merge($matchThese, $matchTheseN, $match);
        $search_data = $array;


        //dd($treatments);
        return view('laralum.treatment.tokens', compact('treatments', 'count', 'error', 'treatment_date', 'search', 'search_data'));
    }


    public function updatePatientTreatment(Request $request, $id)
    {
        Laralum::permissionToAccess('account.management');
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
                                'status' => TreatmentToken::STATUS_COMPLETED
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
        return ['id' => $id, 'status' => $treatment->getStatusOptions($treatment->status), 'reason' => $treatment->not_attended_reason];
    }


    public function export(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.treatments');
        $treatments = Treatment::select('*')->orderBy('created_at', 'DESC');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $treatments->count();
        if ($pagination == true) {
            $treatments = $treatments->paginate($per_page);
        } else {
            $treatments = $treatments->get();
        }

        $treatments_array[] = [
            'Title', 'Duration', 'Price', 'Department'
        ];
        foreach ($treatments as $treatment) {    
            if($treatment->department){
                $title = $treatment->department->title;
            }
            else{
                $title  = "All";
            }
            $treatments_array[] = [
                $treatment->title,
                $treatment->getDuration(),
                $treatment->price,
                $title
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('treatments', function ($excel) use ($treatments_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Treatments');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($treatments_array) {
                $sheet->fromArray($treatments_array, null, 'A1', false, false);
            });
        });

        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = \PDF::loadView('booking.pdf', array('data' => $treatments_array));
            return $pdf->download('treatments_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        //echo '<pre>'; print_r($request->all());exit;
        //if (file_exists($file)) {
        if ($file) {
            $path = $request->file('file')->getRealPath();
            // print_r($path);exit;
            $excel = app('excel');
            Excel::load($path)->chunk(1000, function ($reader) {

                foreach ($reader->toArray() as $row_ar) {

                    if ($row_ar['department'] == 'Ayurveda') {
                        $dept = Department::getAyurvedId();
                    }else {
                        $dept = Department::where('title', $row_ar['department'])->first();
                        if ($dept) {
                            $dept = $dept->id;
                        }
                    }


                    if (!empty($dept)) {
                        Treatment::importData($row_ar, $dept);
                    }
                }
            });
        }
        return redirect()->back();
    }
}
