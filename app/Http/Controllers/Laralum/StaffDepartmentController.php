<?php

namespace App\Http\Controllers\Laralum;

use App\Settings;
use App\StaffDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class StaffDepartmentController extends Controller
{
    /**
     * staff_department listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        $models = StaffDepartment::select('*');
        $search = false;
        $option_ar = [];
        if ($request->get('filter_title')) {
            $search = true;
            $option_ar[] = "Title";
            $models = $models->where('title', 'LIKE', '%' . $request->get('filter_title') . "%");
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
            $models = $models->orderBy('staff_departments.created_at', 'DESC')->paginate($per_page);
        } else {
            $models = $models->orderBy('staff_departments.created_at', 'DESC')->get();
        }

        return view('laralum.staff_departments.index', compact('models', 'count', 'error', 'search'));
    }

    public function edit($id)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        $model = StaffDepartment::find($id);

        # Return the view
        return view('laralum.staff_departments.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        # Find the row
        Laralum::permissionToAccess('admin.staff_departments.list');
        $staff_department = StaffDepartment::findOrFail($id);

        try {

            if ($staff_department->setData($request)) {
                $staff_department->save();
                return redirect()->route('Laralum::admin.staff_departments')->with('success', 'Staff Department edited successfully.');
            } else {
                return redirect()->route('Laralum::admin.staff_departments')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the staff_department, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.staff_departments')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add staff_department for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        $model = new StaffDepartment();

        # Return the view
        return view('laralum.staff_departments.create', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
        $rules = StaffDepartment::rules();
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {

            $model = new StaffDepartment();

            if ($model->setData($request)) {
                $model->save();
                return redirect()->route('Laralum::admin.staff_departments')->withInput()->with('success', 'Staff Department added successfully.');
            } else {
                return redirect()->route('Laralum::admin.staff_departments')->withInput()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the staff department, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::admin.staff_departments')->withInput()->with('error', 'Something went wrong. Please try again later.' . $e->getMessage());
        }

    }

    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.staff_departments.list');

        # Select StaffDepartment
        $staff_department = StaffDepartment::findOrFail($id);

        if ($staff_department->customDelete()) {
            # Redirect the admin
            return redirect()->route('Laralum::admin.staff_departments')->with('success', trans('laralum.msg_department_deleted'));
        }
        //}

        return redirect()->route('Laralum::admin.staff_departments')->with('error', trans('laralum.msg_department_delete_not_allowed'));
    }


    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
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

        $models = StaffDepartment::select('staff_departments.*')->orderBy('staff_departments.created_at', 'DESC');

        if ($search == true) {
            $models = StaffDepartment::select('staff_departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('staff_departments.created_at', 'DESC');
            $departments = $models->get();
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $departments = $models->paginate($per_page);
            } else {
                $departments = $models->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/staff_departments/_list', ['models' => $departments, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printStaffDepartments(Request $request)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
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

        $models = StaffDepartment::select('staff_departments.*')->orderBy('staff_departments.created_at', 'DESC');

        if ($search == true) {
            $models = StaffDepartment::select('staff_departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('staff_departments.created_at', 'DESC');
            $departments = $models->get();
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $departments = $models->paginate($per_page);
            } else {
                $departments = $models->get();
            }
        }

        # Return the view
        return view('laralum/staff_departments/print_staff_departments', [
            'models' => $departments,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportStaffDepartments(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.staff_departments.list');
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

        $models = StaffDepartment::select('staff_departments.*')->orderBy('staff_departments.created_at', 'DESC');

        if ($search == true) {
            $models = StaffDepartment::select('staff_departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('staff_departments.created_at', 'DESC');
            $departments = $models->get();
            $count = $models->count();
        } else {
            $count = $models->count();
            if ($pagination == true) {
                $departments = $models->paginate($per_page);
            } else {
                $departments = $models->get();
            }
        }


        $all_ar[] = [
            'Name',
            'Description',
        ];

        foreach ($departments as $department)
        {
            $all_ar[] = [
                $department->title,
                $department->description,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Staff Departments', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Staff Departments List');

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
            return $pdf->download('staff_department_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');

    }

}
