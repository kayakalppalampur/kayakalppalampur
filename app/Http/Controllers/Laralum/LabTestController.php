<?php

namespace App\Http\Controllers\Laralum;

use App\LabTest;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Monolog\Handler\IFTTTHandler;

class LabTestController extends Controller
{
    //
    /**
     * lab_test listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');
        $lab_tests = LabTest::select('*')->orderBy('created_at', "DESC");

        if (!\Auth::user()->isAdmin()) {
            $lab_tests = $lab_tests->orderBy('created_at', "DESC")->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $lab_tests->count();
        if ($pagination == true) {
            $lab_tests = $lab_tests->paginate($per_page);
        } else {
            $lab_tests = $lab_tests->get();
        }

        return view('laralum.lab_test.index', compact('lab_tests', 'count'));
    }

    public function printTests(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');
        $lab_tests = LabTest::select('*')->orderBy('created_at', "DESC");

        if (!\Auth::user()->isAdmin()) {
            $lab_tests = $lab_tests->orderBy('created_at', "DESC")->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $lab_tests->count();
        if ($pagination == true) {
            $lab_tests = $lab_tests->paginate($per_page);
        } else {
            $lab_tests = $lab_tests->get();
        }

        $print = true;
        return view('laralum.lab_test.print-lab-tests', compact('lab_tests', 'print'));

    }

    /**
     * lab_test details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');
        $lab_test = LabTest::find($id);

        return view('laralum.lab_test.view', compact('lab_test'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');

        # Find the lab_test
        $row = LabTest::findOrFail($id);
        \Session::put('lab_test_id', $id);

        # Get all the data
        $data_index = 'lab_tests';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/lab_test/edit', [
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
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');

        # Find the row
        $lab_test = LabTest::findOrFail($id);

        try {

            if ($lab_test->setData($request)) {
                $lab_test->save();
                return redirect()->route('Laralum::lab-tests')->with('success', 'Lab Test edited successfully.');
            } else {
                return redirect()->route('Laralum::lab-tests')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the lab_test, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::lab-tests')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add lab_test for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');

        # Get all the data
        $data_index = 'lab_tests';
        require('Data/Create/Get.php');

        return view('laralum.lab_test.create',
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
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');
        $rules = LabTest::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $lab_test = LabTest::where('name', $request->get("name"))->first();
            if ($lab_test == null)
                $lab_test = new LabTest();

            if ($lab_test->setData($request)) {
                $lab_test->save();
                return redirect()->route('Laralum::lab-tests')->with('success', 'Lab Test added successfully.');
            } else {
                return redirect()->route('Laralum::lab-tests')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback question, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::lab-tests')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');

        # Select LabTest
        $lab_test = LabTest::findOrFail($id);
        # Delete LabTest
        $lab_test->customDelete();
        # Redirect the admin
        return redirect()->route('Laralum::lab-tests')->with('success', trans('laralum.msg_lab_test_deleted'));

    }


    public function export(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.lab_tests');
        $lab_tests = LabTest::select('*')->orderBy('created_at', "DESC");
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $count = $lab_tests->count();

        if ($pagination == true) {
            $lab_tests = $lab_tests->paginate($per_page);
        } else {
            $lab_tests = $lab_tests->get();
        }
        $lab_tests_array[] = [
            'Name', 'Department', 'Price(in INR)'
        ];
        foreach ($lab_tests as $lab_test) {
            if($lab_test->department){
                $title = $lab_test->department->title;
            }
            else{
                $title = "All";
            }
            $lab_tests_array[] = [
                $lab_test->name,
                $title,
                $lab_test->price
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('lab_tests', function ($excel) use ($lab_tests_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Lab Tests');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($lab_tests_array) {
                $sheet->fromArray($lab_tests_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $lab_tests_array));
            return $pdf->download('lab_tests.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function import(Request $request)
    {
        $path = '/home/user/Documents/antar-docs/data_to_feed/lab_tests.xlsx';
        // print_r($path);exit;
        $excel = app('excel');

        Excel::load($path)->chunk(1000, function ($reader) {

            foreach ($reader->toArray() as $row_ar) {
                $name = $row_ar['name'];
                $price = $row_ar['price'];
                $duration = str_replace('minutes', '', $row_ar['durationminutes']);
                $category = LabTest::CATEGORY_Biochemistry;

                if ($row_ar['category'] == 'Haematology') {
                    $category = LabTest::CATEGORY_Haematology;
                }

                if ($row_ar['category'] == 'Serology') {
                    $category = LabTest::CATEGORY_Serology;
                }
                $type = LabTest::TYPE_EXTERNAL;
                if ($row_ar['type_internal_external'] == 'Internal') {
                    $type = LabTest::TYPE_INTERNAL;
                }

                $labtest = LabTest::where('name', 'LIKE',$name)->first();
                if ($labtest == null) {
                    $labtest = new LabTest();
                }
                $labtest->name = $name;
                $labtest->category_id = $category;
                $labtest->type = $type;
                $labtest->department_id = 0;
                $labtest->duration = $duration;
                $labtest->price = $price;
                $labtest->save();
            }
        });

        return redirect()->back();
    }

}
