<?php

namespace App\Http\Controllers\Laralum;

use App\Department;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class DepartmentController extends Controller
{
    //
    /**
     * department listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.doctor_departments.list');
        $departments = Department::select('*');

        if (!\Auth::user()->isAdmin()) {
            $departments = $departments->where('created_by', \Auth::user()->id);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $departments = $departments->orderBy('created_at', 'DESC')->paginate($per_page);
        } else {
            $departments = $departments->orderBy('created_at', 'DESC')->get();
        }

        return view('laralum.departments.index', compact('departments'));
    }

    /**
     * department details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.doctor_departments.list');
        $department = Department::find($id);

        return view('laralum.departments.view', compact('department'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.doctor_departments.list');

        # Find the department
        $row = Department::findOrFail($id);
        \Session::put('department_id', $id);

        # Get all the data
        $data_index = 'departments';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/departments/edit', [
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
        Laralum::permissionToAccess('admin.doctor_departments.list');

        # Find the row
        $department = Department::findOrFail($id);

        try {

            if ($department->setData($request)) {
                $department->save();
                return redirect()->route('Laralum::departments')->with('success', 'Department edited successfully.');
            } else {
                return redirect()->route('Laralum::departments')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the department, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::departments')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add department for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.doctor_departments.list');
        # Get all the data
        $data_index = 'departments';
        require('Data/Create/Get.php');

        return view('laralum.departments.create',
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
        Laralum::permissionToAccess('admin.doctor_departments.list');
        $rules = Department::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $department = Department::where(\DB::raw('lower(title)'), strtolower($request->get('title')))->first();
            if ($department == null)
                $department = new Department();

            if ($department->setData($request)) {
                $department->save();
                return redirect()->route('Laralum::departments')->with('success', 'Department added successfully.');
            } else {
                return redirect()->route('Laralum::departments')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the department, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::departments')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.doctor_departments.list');

        # Select Department
        $department = Department::findOrFail($id);

        # Check Department Users
        /*   if ($department->isAllowed()) {*/
        # Delete Department
        if ($department->customDelete()) {
            # Redirect the admin
            return redirect()->route('Laralum::departments')->with('success', trans('laralum.msg_department_deleted'));
        }
        //}

        return redirect()->route('Laralum::departments')->with('error', trans('laralum.msg_department_delete_not_allowed'));

    }

    public function getDepartmentDoctors(Request $request)
    {
        $id = $request->get('department_id');
        $html = "<option></option>";
        $department = Department::find($id);
        if ($department != null) {
            $doctors = $department->getDoctors();
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
            $id = $request->get('department_id');
        $old_val = explode(',', $request->get("old_val"));

        $html = "<option></option>";
        $department = Department::find($id);
        if ($department != null) {
            $treatments = $department->treatments;
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
        Laralum::permissionToAccess('admin.doctor_departments.list');
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

        $models = Department::select('departments.*')->orderBy('departments.created_at', 'DESC');

        if ($search == true) {
            $models = Department::select('departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('departments.created_at', 'DESC');

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
            'html' => view('laralum/departments/_list', ['departments' => $departments, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printDepartments(Request $request)
    {

        Laralum::permissionToAccess('admin.doctor_departments.list');
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

        $models = Department::select('departments.*')->orderBy('departments.created_at', 'DESC');

        if ($search == true) {
            $models = Department::select('departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('departments.created_at', 'DESC');

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


        return view('laralum/departments/print_departments', [
            'departments' => $departments,
            'print' => true
        ]);
    }

    public function exportDepartments(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.doctor_departments.list');
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

        $models = Department::select('departments.*')->orderBy('departments.created_at', 'DESC');

        if ($search == true) {
            $models = Department::select('departments.*')->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('departments.created_at', 'DESC');

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
            'Title',
            'Description',
        ];

        foreach ($departments as $department)
        {
            $all_ar[] = [
                $department->title,
                $department->description
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Departments', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Departments List');

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
            return $pdf->download('departments_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
