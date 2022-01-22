<?php

namespace App\Http\Controllers\Laralum;

use App\Bill;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class BillController extends Controller
{
    //
    /**
     * bill listing
     * @return View
     */
    public function index(Request $request)
    {
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        $bills = Bill::select('*');

        if (!\Auth::user()->isAdmin()) {
            $bills = $bills->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $bills = $bills->orderBy('created_at', 'DESC')->paginate($per_page);
        } else {
            $bills = $bills->orderBy('created_at', 'DESC')->get();
        }

        return view('laralum.bills.index', compact('bills'));
    }

    /**
     * bill details with replies
     * @return View
     */
    public function view($id)
    {
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        $bill = Bill::find($id);

        return view('laralum.bills.view', compact('bill'));
    }

    public function edit($id)
    {
        # Check permissions
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2

        # Find the bill
        $row = Bill::findOrFail($id);
        \Session::put('bill_id', $id);

        # Get all the data
        $data_index = 'bills';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/bills/edit', [
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
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2

        # Find the row
        $bill = Bill::findOrFail($id);

        try {

            if ($bill->setData($request)) {
                $bill->save();
                return redirect()->route('Laralum::bills')->with('success', 'Bill edited successfully.');
            } else {
                return redirect()->route('Laralum::bills')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the bill, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::bills')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add bill for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        # Get all the data
        $data_index = 'bills';
        require('Data/Create/Get.php');

        return view('laralum.bills.create',
            [
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
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        $rules = Bill::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $bill = Bill::where(\DB::raw('lower(title)'), strtolower($request->get('title')))->first();
            if ($bill == null)
                $bill = new Bill();

            if ($bill->setData($request)) {
                $bill->save();
                return redirect()->route('Laralum::bills')->with('success', 'Bill added successfully.');
            } else {
                return redirect()->route('Laralum::bills')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the bill, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::bills')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2

        # Select Bill
        $bill = Bill::findOrFail($id);

        # Check Bill Users
        /*   if ($bill->isAllowed()) {*/
        # Delete Bill
        if ($bill->customDelete()) {
            # Redirect the admin
            return redirect()->route('Laralum::bills')->with('success', trans('laralum.msg_bill_deleted'));
        }
        //}

        return redirect()->route('Laralum::bills')->with('error', trans('laralum.msg_bill_delete_not_allowed'));

    }

    public function getBillDoctors(Request $request)
    {
        $id = $request->get('bill_id');
        $html = "<option></option>";
        $bill = Bill::find($id);
        if ($bill != null) {
            $doctors = $bill->getDoctors();
            if ($doctors->count() > 0) {
                $html = "";
                foreach ($doctors as $doctor) {
                    $html .= "<option value='$doctor->id'>" . $doctor->name . '</option>';
                }
            }
        }
        return $html;
    }

    public function getTreatments(Request $request, $id)
    {
        if ($id == null)
            $id = $request->get('bill_id');
        $old_val = explode(',', $request->get("old_val"));

        $html = "<option></option>";
        $bill = Bill::find($id);
        if ($bill != null) {
            $treatments = $bill->treatments;
            if ($treatments->count() > 0) {
                $html = "";
                foreach ($treatments as $treatment) {
                    $selected = "";
                    if (in_array($treatment->id, $old_val)) {
                        $selected = "selected";
                    }
                    $html .= "<option data-price='" . $treatment->price . "' value='$treatment->id'" . $selected . ">" . $treatment->title . '</option>';
                }
            }
        }
        return $html;
    }

    public function ajaxUpdate(Request $request)
    {
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
<<<<<<< HEAD
        if (!empty($request->has('bill_date'))) {
            $option_ar[] = "Bill Date";
            $search = true;
            $matchThese['bill_date'] = $request->get('bill_date');
        }
        $bill_from_date = null;
        $bill_to_date = null;
        $s_data = [];
         if (!empty($request->has('bill_from_date')) ){
            $option_ar[] = "Bill From Date";
            $search = true;
            $bill_from_date = date('Y-m-d H:i:s', strtotime($request->get('bill_from_date')));
            $s_data['bill_from_date'] = $request->get('bill_from_date');
        } 

        if (!empty($request->has('bill_to_date') )) {
            $option_ar[] = "Bill To Date";
            $search = true;
            $bill_to_date = date('Y-m-d H:i:s', strtotime($request->get('bill_to_date')));
            $s_data['bill_to_date'] = $request->get('bill_to_date');
=======
        if (!empty($request->has('title'))) {
            $option_ar[] = "Title";
            $search = true;
            $matchThese['title'] = $request->get('title');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
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

<<<<<<< HEAD
        $models = Bill::select('patient_bills.*')->orderBy('patient_bills.created_at', 'DESC');

        if ($search == true) {
            $models = Bill::select('patient_bills.*');
            
            if(!empty($matchThese)) {
                $models =  $models->where(function ($query) use ($matchThese, $bill_from_date, $bill_to_date) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                } });
            }

            if ($bill_from_date && $bill_to_date) {
                $models =  $models->whereDate('created_at', '>=', $bill_from_date)->whereDate('created_at', '<=', $bill_to_date);
            }            

            $bills = $models->orderBy('patient_bills.created_at', 'DESC')->get();
=======
        $models = Bill::select('bills.*')->orderBy('bills.created_at', 'DESC');

        if ($search == true) {
            $models = Bill::select('bills.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('bills.created_at', 'DESC');

            $bills = $models->get();
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $bills = $models->paginate($per_page);
            } else {
                $bills = $models->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
<<<<<<< HEAD


        $search_data = array_merge($matchThese, $s_data);
        return [
            'html' => view('laralum/bills/_list', ['bills' => $bills, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' =>  $search_data ])->render()
=======
        return [
            'html' => view('laralum/bills/_list', ['bills' => $bills, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        ];

    }

<<<<<<< HEAD
    public function print($id) 
    {
        Laralum::permissionToAccess('admin.admin_settings.bills');
        $bill = Bill::with('booking')->find($id);
        $data['back'] = 'bills';
        if ($bill) {
            $data['bill'] = $bill;
            return view('laralum.booking.print-generated-bill', $data);
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function printBills(Request $request)
    {

        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
    public function printBills(Request $request)
    {

        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
<<<<<<< HEAD
        if (!empty($request->has('bill_date'))) {
            $option_ar[] = "Bill Date";
            $search = true;
            $matchThese['bill_date'] = $request->get('bill_date');
        }
        $bill_from_date = null;
        $bill_to_date = null;
        $s_data = [];
         if (!empty($request->has('bill_from_date')) ){
            $option_ar[] = "Bill From Date";
            $search = true;
            $bill_from_date = date('Y-m-d H:i:s', strtotime($request->get('bill_from_date')));
            $s_data['bill_from_date'] = $request->get('bill_from_date');
        } 

        if (!empty($request->has('bill_to_date') )) {
            $option_ar[] = "Bill To Date";
            $search = true;
            $bill_to_date = date('Y-m-d H:i:s', strtotime($request->get('bill_to_date')));
            $s_data['bill_to_date'] = $request->get('bill_to_date');
=======

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['title'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['title'] = $search_data['title'];
            }
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
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

<<<<<<< HEAD
        $models = Bill::select('patient_bills.*')->orderBy('patient_bills.created_at', 'DESC');

        if ($search == true) {
            $models = Bill::select('patient_bills.*');
            
            if(!empty($matchThese)) {
                $models =  $models->where(function ($query) use ($matchThese, $bill_from_date, $bill_to_date) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                } });
            }

            if ($bill_from_date && $bill_to_date) {
                $models =  $models->whereDate('created_at', '>=', $bill_from_date)->whereDate('created_at', '<=', $bill_to_date);
            }            

            $bills = $models->orderBy('patient_bills.created_at', 'DESC')->get();
=======
        $models = Bill::select('bills.*')->orderBy('bills.created_at', 'DESC');

        if ($search == true) {
            $models = Bill::select('bills.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('bills.created_at', 'DESC');

            $bills = $models->get();
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $bills = $models->paginate($per_page);
            } else {
                $bills = $models->get();
            }
        }


<<<<<<< HEAD
        return view('laralum/bills/print', [
=======
        return view('laralum/bills/print_bills', [
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
            'bills' => $bills,
            'print' => true
        ]);
    }

    public function exportBills(Request $request, $type)
    {
<<<<<<< HEAD
        Laralum::permissionToAccess('admin.admin_settings.bills');
=======
        Laralum::permissionToAccess('admin.doctor_bills.list');
>>>>>>> 5ed0c76eb7c3f854e777a8aa4decfe2b2a810fb2
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

            if (!empty($search_data['title'])) {
                $option_ar[] = "Title";
                $search = true;
                $matchThese['title'] = $search_data['title'];
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

        $models = Bill::select('bills.*')->orderBy('bills.created_at', 'DESC');

        if ($search == true) {
            $models = Bill::select('bills.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('bills.created_at', 'DESC');

            $bills = $models->get();
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $bills = $models->paginate($per_page);
            } else {
                $bills = $models->get();
            }
        }


        $all_ar[] = [
            'Title',
            'Description',
        ];

        foreach ($bills as $bill)
        {
            $all_ar[] = [
                $bill->title,
                $bill->description
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Bills', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Bills List');

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
            return $pdf->download('bills_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
