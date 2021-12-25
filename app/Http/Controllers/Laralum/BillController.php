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
        Laralum::permissionToAccess('admin.doctor_bills.list');
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
        Laralum::permissionToAccess('admin.doctor_bills.list');
        $bill = Bill::find($id);

        return view('laralum.bills.view', compact('bill'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.doctor_bills.list');

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
        Laralum::permissionToAccess('admin.doctor_bills.list');

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
        Laralum::permissionToAccess('admin.doctor_bills.list');
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
        Laralum::permissionToAccess('admin.doctor_bills.list');
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
        Laralum::permissionToAccess('admin.doctor_bills.list');

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
        Laralum::permissionToAccess('admin.doctor_bills.list');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->has('title'))) {
            $option_ar[] = "Title";
            $search = true;
            $matchThese['title'] = $request->get('title');
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
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/bills/_list', ['bills' => $bills, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printBills(Request $request)
    {

        Laralum::permissionToAccess('admin.doctor_bills.list');
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


        return view('laralum/bills/print_bills', [
            'bills' => $bills,
            'print' => true
        ]);
    }

    public function exportBills(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.doctor_bills.list');
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
