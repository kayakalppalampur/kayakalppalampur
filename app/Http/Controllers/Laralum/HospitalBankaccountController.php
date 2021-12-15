<?php

namespace App\Http\Controllers\Laralum;

use App\HospitalBankaccount;
use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use PDF;

class HospitalBankaccountController extends Controller
{

    /**
     * hospital_bankaccount listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $models = HospitalBankaccount::select('*')->orderBy('hospital_bankaccount.created_at', 'DESC');
        $search = false;
        $option_ar = [];
        if ($request->get('filter_name')) {
            $search = true;
            $option_ar[] = "Bank Name";
            $models = $models->where('bank_name', 'LIKE', '%' . $request->get('filter_name') . "%");
        }
        if ($request->get('filter_account_no')) {
            $option_ar[] = "AccountNo";
            $search = true;
            $models = $models->where('account_no', 'LIKE', '%' . $request->get('filter_account_no') . "%");
        }
        if ($request->get('date')) {
            $option_ar[] = "Date";
            $search = true;
            $date = date("Y-m-d", strtotime($request->get('date')));
            $models = $models->where('date', 'LIKE', '%' . $date . "%");
        }
        if ($request->get('filter_account_type')) {
            $option_ar[] = "Account type";
            $search = true;
            $models = $models->where('account_type', $request->get('filter_account_type'));
        }
        if ($request->get('filter_branch')) {
            $option_ar[] = "Branch";
            $search = true;
            $models = $models->where('branch', $request->get('filter_branch'));
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
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        return view('laralum.hospital_bankaccount.index', compact('models', 'count', 'error', 'search'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $model = HospitalBankaccount::find($id);

        # Return the view
        return view('laralum.hospital_bankaccount.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        # Find the row
        $hospital_bankaccount = HospitalBankaccount::findOrFail($id);

        try {

            if ($hospital_bankaccount->setData($request)) {
                $hospital_bankaccount->save();
                return redirect()->route('Laralum::admin.hospital_bank_account')->with('success', 'Bank account edited successfully.');
            } else {
                return redirect()->route('Laralum::admin.hospital_bank_account')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the hospital_bankaccount, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.hospital_bank_account')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add hospital_bankaccount for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $model = new HospitalBankaccount();

        # Return the view
        return view('laralum.hospital_bankaccount.create', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $rules = HospitalBankaccount::rules();
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {

            $model = new HospitalBankaccount();

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::admin.hospital_bank_account')->withInput()->with('success', 'Hospital Info added successfully.');
            } else {
                return redirect()->route('Laralum::admin.hospital_bank_account')->withInput()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the hospital bankaccount, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.hospital_bank_account')->withInput()->with('error', 'Something went wrong. Please try again later.');
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');

        # Select HospitalBankaccount
        $hospital_bankaccount = HospitalBankaccount::findOrFail($id);

        $hospital_bankaccount->delete();
        return redirect()->route('Laralum::admin.hospital_bank_account')->with('success', 'Successfully Deleted hospital bank account.');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;

        $option_ar = [];

        if ($request->get('bank_name')) {
            $search = true;
            $option_ar[] = "Bank Name";
            $matchThese['bank_name'] = $request->get('bank_name');
        }
        if ($request->get('account_no')) {
            $option_ar[] = "AccountNo";
            $search = true;
            $matchThese['account_no'] = $request->get('account_no');
        }
        if ($request->get('date')) {
            $option_ar[] = "Date";
            $search = true;
            $matchThese['date'] = date("Y-m-d", strtotime($request->get('date')));

        }
        if ($request->get('opening_balance')) {
            $option_ar[] = "Account type";
            $search = true;
            $matchThese['opening_balance'] = $request->get('opening_balance');
        }
        if ($request->get('account_type')) {
            if ($request->get('account_type') != 'all') {
                $option_ar[] = "Account type";
                $search = true;
                $matchThese['account_type'] = $request->get('account_type');
            }
        }
        if ($request->get('branch')) {
            $option_ar[] = "Branch";
            $search = true;
            $matchThese['branch'] = $request->get('branch');
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = HospitalBankaccount::select('hospital_bankaccount.*')->orderBy('hospital_bankaccount.created_at', 'DESC');

        if ($search == true) {
            $models = HospitalBankaccount::select('hospital_bankaccount.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('hospital_bankaccount.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if($request->get('date')) {
                $matchThese['date'] = date("d-m-Y", strtotime($request->get('date')));
            }
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/hospital_bankaccount/_list', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];
    }

    public function printHospitalBankAccount(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;

        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['bank_name'])) {
                $search = true;
                $option_ar[] = "Bank Name";
                $matchThese['bank_name'] = $search_data['bank_name'];
            }
            if (!empty($search_data['account_no'])) {
                $option_ar[] = "AccountNo";
                $search = true;
                $matchThese['account_no'] = $search_data['account_no'];
            }
            if (!empty($search_data['date'])) {
                $option_ar[] = "Date";
                $search = true;
                $matchThese['date'] = date("Y-m-d", strtotime($search_data['date']));

            }
            if (!empty($search_data['opening_balance'])) {
                $option_ar[] = "Account type";
                $search = true;
                $matchThese['opening_balance'] = $search_data['opening_balance'];
            }
            if (!empty($search_data['account_type'])) {
                if ($request->get('account_type') != 'all') {
                    $option_ar[] = "Account type";
                    $search = true;
                    $matchThese['account_type'] = $search_data['account_type'];
                }
            }
            if (!empty($search_data['branch'])) {
                $option_ar[] = "Branch";
                $search = true;
                $matchThese['branch'] = ta['branch'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = HospitalBankaccount::select('hospital_bankaccount.*')->orderBy('hospital_bankaccount.created_at', 'DESC');

        if ($search == true) {
            $models = HospitalBankaccount::select('hospital_bankaccount.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('hospital_bankaccount.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if($request->get('date')) {
                $matchThese['date'] = date("d-m-Y", strtotime($request->get('date')));
            }
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        return view('laralum/hospital_bankaccount/print_hospital_bankaccount', [
            'models' => $models,
            'count'=> $count,
            'search' => $search,
            'print' => true

        ]);
    }

    public function exportHospitalBankAccount(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.hospital_bank_account');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;

        $option_ar = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['bank_name'])) {
                $search = true;
                $option_ar[] = "Bank Name";
                $matchThese['bank_name'] = $search_data['bank_name'];
            }
            if (!empty($search_data['account_no'])) {
                $option_ar[] = "AccountNo";
                $search = true;
                $matchThese['account_no'] = $search_data['account_no'];
            }
            if (!empty($search_data['date'])) {
                $option_ar[] = "Date";
                $search = true;
                $matchThese['date'] = date("Y-m-d", strtotime($search_data['date']));

            }
            if (!empty($search_data['opening_balance'])) {
                $option_ar[] = "Account type";
                $search = true;
                $matchThese['opening_balance'] = $search_data['opening_balance'];
            }
            if (!empty($search_data['account_type'])) {
                if ($request->get('account_type') != 'all') {
                    $option_ar[] = "Account type";
                    $search = true;
                    $matchThese['account_type'] = $search_data['account_type'];
                }
            }
            if (!empty($search_data['branch'])) {
                $option_ar[] = "Branch";
                $search = true;
                $matchThese['branch'] = ta['branch'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $models = HospitalBankaccount::select('hospital_bankaccount.*')->orderBy('hospital_bankaccount.created_at', 'DESC');

        if ($search == true) {
            $models = HospitalBankaccount::select('hospital_bankaccount.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('hospital_bankaccount.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if($request->get('date')) {
                $matchThese['date'] = date("d-m-Y", strtotime($request->get('date')));
            }
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        $all_ar[] = [
           'Bank Name',
            'Account No',
            'Date',
            'Opening Balance',
            'Account Type',
            'Branch',
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->bank_name,
                $model->account_no,
                date('d-m-Y',strtotime($model->date)),
                $model->opening_balance,
                $model->getTypeOptions($model->account_type),
                $model->branch,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Hospital Bank Accounts', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Hospital Bank Accounts');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($all_ar) {
                $sheet->fromArray($all_ar, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            $pdf = PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('hospital_bank_accounts.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
