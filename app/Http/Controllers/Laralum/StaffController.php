<?php

namespace App\Http\Controllers\Laralum;

use App\Settings;
use App\Staff;
use App\StaffDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class StaffController extends Controller
{
    /**
     * tax_detail listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.staff.list');
        $models = Staff::select('*')->orderBy('staff.created_at', 'DESC');
        $search = false;
        $option_ar = [];
        if ($request->get('filter_name')) {
            $search = true;
            $option_ar[] = "Name";
            $models = $models->where('name', 'LIKE', '%' . $request->get('filter_name') . "%");
        }

        if ($request->get('filter_department')) {
            $search = true;
            $option_ar[] = "Department";
            $models = $models->where('department', 'LIKE', '%' . $request->get('filter_department') . "%");
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

        return view('laralum.staff.index', compact('models', 'count', 'error', 'search'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        # Find the role

        $model = Staff::find($id);
        /*
                if(!$model->allow_editing and !Laralum::loggedInuser()->su) {
                    abort(403, trans('laralum.error_editing_disabled'));
                }*/

        # Get all the data
        $data_index = 'staff';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/staff/edit', [
            'row' => $model,
            'model' => $model,
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
        Laralum::permissionToAccess('admin.staff_departments.list');
        # Find the row
        $tax_detail = Staff::findOrFail($id);

        try {

            if ($tax_detail->setData($request)) {
                $tax_detail->save();
                return redirect()->route('Laralum::staff')->with('success', 'Staff member edited successfully.');
            } else {
                return redirect()->route('Laralum::staff')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the staff member, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::staff')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add tax_detail for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        # Get all the data
        $data_index = 'staff';
        require('Data/Create/Get.php');

        # All permissions
        $permissions = Laralum::permissions();

        # Return the view
        return view('laralum/staff/create', [
            'model' => new Staff(),
            'fields' => $fields,
            'confirmed' => $confirmed,
            'encrypted' => $encrypted,
            'hashed' => $hashed,
            'masked' => $masked,
            'permissions' => $permissions,
            'table' => $table,
            'code' => $code,
            'wysiwyg' => $wysiwyg,
            'relations' => $relations,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        $rules = Staff::rules();
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {

            $model = new Staff();

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::staff')->withInput()->with('success', 'Staff member added successfully.');
            } else {
                return redirect()->route('Laralum::staff')->withInput()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the staff member, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::staff')->withInput()->with('error', 'Something went wrong. Please try again later.' . $e->getMessage());
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.staff_departments.list');

        # Select Staff
        $staff = Staff::findOrFail($id);

        $staff->customDelete();
        return redirect()->route('Laralum::staff')->with('success', 'Successfully Deleted staff member.');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.staff.list');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        if (!empty($request->has('name'))) {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['name'] = $request->get('name');
        }
        $dept = "";
        if (!empty($request->has('department'))) {
            $option_ar[] = "Department";
            $search = true;
            $dept = $request->get('department');
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

        $models = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');

        if ($search == true) {
            $models = Staff::select('staff.*')->where(function ($query) use ($matchThese, $dept) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

                if($dept) {
                    $query->where('department', $dept);
                }
            })
                ->orderBy('staff.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if(!empty($dept)) {
                $matchThese['department'] = $request->get('department');
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
            'html' => view('laralum/staff/_list', ['models' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printStaff(Request $request)
    {
        Laralum::permissionToAccess('admin.staff.list');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $dept = "";

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);
            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            if (!empty($search_data['department'])) {
                $option_ar[] = "Department";
                $search = true;
                $dept = $search_data['department'];
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

        $models = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');

        if ($search == true) {
            $models = Staff::select('staff.*')->where(function ($query) use ($matchThese, $dept) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

                if($dept) {
                    $query->where('department', $dept);
                }

            })
                ->orderBy('staff.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if(!empty($search_data['department'])) {
                $matchThese['department'] = $search_data['department'];
            }
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $models = $models->paginate($per_page);
            } else {
                $models = $models->get();
            }
        }
        # Return the view
        return view('laralum/staff/print_staff', [
            'models' => $models,
            'count' => $count,
            'print' => true

        ]);
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
                    $dept = StaffDepartment::where('title' , $row_ar['department'])->first();
                    if (empty($dept)) {
                        $dept = StaffDepartment::create([
                            'title' => $row_ar['department'],
                            'description' => '',
                            'status' => 1
                        ]);
                    }

                    if ($dept) {
                        Staff::importData($row_ar, $dept->id);
                    }
                }
            });
        }
        return redirect()->back();
    }

    public function exportStaff(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.staff.list');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $dept = "";

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);
            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }
            if (!empty($search_data['department'])) {
                $option_ar[] = "Department";
                $search = true;
                $dept = $search_data['department'];
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

        $models = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');

        if ($search == true) {
            $models = Staff::select('staff.*')->where(function ($query) use ($matchThese, $dept) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }

                if($dept) {
                    $query->where('department', $dept);
                }
            })
                ->orderBy('staff.created_at', 'DESC');
            $models = $models->get();
            $count = $models->count();

            if(!empty($search_data['department'])) {
                $matchThese['department'] = $search_data['department'];
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
            'Name',
            'Department',
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->name,
                @$model->staffDepartment->title,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Staff', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Staff List');

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
            return $pdf->download('staff_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');

    }
}
