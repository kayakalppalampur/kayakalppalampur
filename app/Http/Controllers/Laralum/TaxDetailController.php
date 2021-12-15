<?php

namespace App\Http\Controllers\Laralum;

use App\Settings;
use App\TaxDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class TaxDetailController extends Controller
{
    /**
     * tax_detail listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        $models = TaxDetail::select('*');
        $search = false;
        $option_ar = [];
        if ($request->get('tax_type')) {
            $search = true;
            $option_ar[] = "Tax Type";
            $models = $models->where('tax_type', 'LIKE', '%' . $request->get('tax_type') . "%");
            $search_data['tax_type'] =  $request->get('tax_amount');
        }

        if ($request->get('tax_amount')) {
            $search = true;
            $option_ar[] = "Tax Amount";
            $models = $models->where('tax_amount', 'LIKE', '%' . $request->get('tax_amount') . "%");
            $search_data['tax_amount'] =  $request->get('tax_amount');
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

        if ($request->ajax()) {
            return view('laralum.tax_details._list', compact('models', 'count', 'error', 'search', 'search_data'));
        }

        return view('laralum.tax_details.index', compact('models', 'count', 'error', 'search'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        $model = TaxDetail::find($id);

        # Return the view
        return view('laralum.tax_details.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        # Find the row
        $tax_detail = TaxDetail::findOrFail($id);

        try {

            if ($tax_detail->setData($request)) {
                $tax_detail->save();
                return redirect()->route('Laralum::admin.tax_details')->with('success', 'Tax Detail edited successfully.');
            } else {
                return redirect()->route('Laralum::admin.tax_details')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the tax_detail, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.tax_details')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add tax_detail for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        $model = new TaxDetail();

        # Return the view
        return view('laralum.tax_details.create', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        $rules = TaxDetail::rules();
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {

            $model = new TaxDetail();

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::admin.tax_details')->withInput()->with('success', 'Tax detail added successfully.');
            } else {
                return redirect()->route('Laralum::admin.tax_details')->withInput()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the Tax Detail, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.tax_details')->withInput()->with('error', 'Something went wrong. Please try again later.' . $e->getMessage());
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.tax_details');

        # Select TaxDetail
        $tax_detail = TaxDetail::findOrFail($id);

        $tax_detail->delete();
        return redirect()->route('Laralum::admin.tax_details')->with('success', 'Successfully Deleted tax type.');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->get('tax_type')) {
            $search = true;
            $option_ar[] = "Tax Type";
            $matchThese['tax_type'] = $request->get('tax_type');
        }
        if ($request->get('tax_amount')) {
            $option_ar[] = "Tax Amount";
            $search = true;
            $matchThese['tax_amount'] = $request->get('tax_amount');
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


        $models = TaxDetail::select('tax_details.*')->orderBy('tax_details.created_at', 'DESC');

        if ($search == true) {
            $models = TaxDetail::select('tax_details.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('tax_details.created_at', 'DESC');
            $count = $models->count();
            $models = $models->get();
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
            'html' => view('laralum/tax_details/_list', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];
    }

    public function printTaxDetails(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
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

            if (!empty($search_data['tax_type'])) {
                $search = true;
                $option_ar[] = "Tax Type";
                $matchThese['tax_type'] = $search_data['tax_type'];
            }
            if (!empty($search_data['tax_amount'])) {
                $option_ar[] = "Tax Amount";
                $search = true;
                $matchThese['tax_amount'] = $search_data['tax_amount'];
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


        $models = TaxDetail::select('tax_details.*')->orderBy('tax_details.created_at', 'DESC');

        if ($search == true) {
            $models = TaxDetail::select('tax_details.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('tax_details.created_at', 'DESC');
            $count = $models->count();
            $models = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        return view('laralum/tax_details/print_tax_details', [
            'models' => $models,
            'count'=> $count,
            'search' => $search,
            'print' => true

        ]);
    }

    public function exportTaxDetails(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.tax_details');
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

            if (!empty($search_data['tax_type'])) {
                $search = true;
                $option_ar[] = "Tax Type";
                $matchThese['tax_type'] = $search_data['tax_type'];
            }
            if (!empty($search_data['tax_amount'])) {
                $option_ar[] = "Tax Amount";
                $search = true;
                $matchThese['tax_amount'] = $search_data['tax_amount'];
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


        $models = TaxDetail::select('tax_details.*');

        if ($search == true) {
            $models = TaxDetail::select('tax_details.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            });
            $count = $models->count();
            $models = $models->get();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }

        $all_ar[] = [
            'Tax Type',
            'Tax Amount',
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->tax_type,
                $model->tax_amount,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Tax Types', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Tax Types Accounts');

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
            return $pdf->download('tax_types_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
