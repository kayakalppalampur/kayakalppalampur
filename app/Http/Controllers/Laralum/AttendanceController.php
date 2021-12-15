<?php

namespace App\Http\Controllers\Laralum;

use App\Attendance;
use App\Leave;
use App\Settings;
use App\Staff;
use App\User;
use DB;
use function foo\func;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Validator;
use Laralum;

class AttendanceController extends Controller
{

    /**
     * attendance listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.attendance.list');
        $to_date = date("d-m-Y");
        $from_date = date("d-m-Y", strtotime("-7 days"));

        if ($request->get('dates')) {
            $dates_ar = array_filter(explode('to', $request->get('dates')));
            $from_date = $dates_ar[0];
            $to_date = isset($dates_ar[1]) ? $dates_ar[1] : date("d-m-Y");
        }

        $dates = Settings::createDateRangeArray($from_date, $to_date);
        $users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');

        $perPage = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($perPage == "All") {
            $perPage = $users->count();
            $users = $users->get();
        } else {
            $users = $users->paginate($perPage);
        }
        $count = count($users);
        $range = $from_date . ' to ' . $to_date;
        return view('laralum.attendance.index', compact('users', 'dates', 'count', 'from_date', 'to_date', 'range'));
    }

    public function printAttendance($date, Request $request)
    {
        $search_data  = [];

        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $serachname = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
                $staffMatchThese['name'] = $search_data['name'];
                $users = Staff::select('staff.*')->where('name', 'like', "%$serachname%")->orderBy('staff.created_at', 'DESC');
            }
            else{
                $users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');
            }
            
        }
        else{
            $users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');
        }

        

        $to_date = date("d-m-Y");
        $from_date = date("d-m-Y", strtotime("-7 days"));

        if ($date != "") {
            $date_arr = explode('to', $date);
            $from_date = $date_arr[0];
            if (isset($date_arr[1])) {
                $to_date = $date_arr[1];
            } else {
                $to_date = date("d-m-Y", strtotime($from_date . " +7 days"));
            }
        }


        $count = $users->count();
        $users = $users->get();
        $range = $date;
        $dates = Settings::createDateRangeArray($from_date, $to_date);

        $print = true;


        if ($request->s && $request->s != 'null') {
           foreach ($dates as $date) {
                if (!empty($request->get('status_' . $date))) {
                    $option_ar[] = "Status";
                    $search = true;
                    $search_data['status_' . $date] = $request->get('status_' . $date);
                }
            } 
        }


        /*foreach ($dates as $date) {
                if (!empty($request->get('status_' . $date))) {
                    $option_ar[] = "Status";
                    $search = true;
                    $search_data['status_' . $date] = $request->get('status_' . $date);
                }
            }
*/
        //return view('laralum.attendance.print-attendance', compact('attendances', 'print', 'date','range'));
        return view('laralum.attendance.print-attendance', compact('users', 'dates', 'count', 'range', 'date', 'print','search_data'));

    }

    /**
     * add attendance for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($date = null)
    {
        $users = Staff::get();

        if ($date == null) {
            $date = date('d-m-Y');
        }

        return view('laralum.attendance.create', compact('users', 'date'));
    }

    public function leaves(Request $request)
    {
        Laralum::permissionToAccess('admin.attendance.list');

        $matchThese = [];
        $staffDepartmentMatchThese = [];
        $staffMatchThese = [];

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $search = false;
        $option_ar = [];

        if ($request->get('name')) {
            $search = true;
            $option_ar[] = 'Name';
            $staffMatchThese['name'] = $request->name;
        }

        if ($request->get('department')) {
            $search = true;
            $option_ar[] = 'Department';
            $staffDepartmentMatchThese['title'] = $request->department;
        }

        if ($request->get('date_start')) {
            $search = true;
            $option_ar[] = 'Start Date';
            $matchThese['date_start'] = date("Y-m-d", strtotime($request->get('date_start')));
        }

        if ($request->get('date_end')) {
            $search = true;
            $option_ar[] = 'End Date';
            $matchThese['date_end'] = date("Y-m-d", strtotime($request->get('date_end')));
        }

        $leaves = Leave::select('leaves.*')->orderBy('created_at', 'DESC');

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        //Define how many items we want to be visible in each page
        $perPage = $request->get("per_page") ? $request->get("per_page") : 10;
        if ($search == true) {
            $leaves = Leave::select('leaves.*')->join('staff', 'staff.id', '=', 'leaves.user_id')->join('staff_departments', 'staff_departments.id', 'staff.department')->where(function ($query) use ($matchThese, $staffDepartmentMatchThese, $staffMatchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where('leaves.' . $key, 'like', "%$match%");
                }

                foreach ($staffMatchThese as $key => $match) {
                    $query->where('staff.' . $key, 'like', "%$match%");
                }
                foreach ($staffDepartmentMatchThese as $key => $match) {
                    $query->where('staff_departments.' . $key, 'like', "%$match%");
                }
            })
                ->orderBy('leaves.created_at', 'DESC');

            $count = $leaves->count();
            $leaves = $leaves->distinct()->get();

            $staffDepartmentMatchThese['department'] = $request->department;

            if (!empty($matchThese['date_start'])) {
                $matchThese['date_start'] = date("d-m-Y", strtotime($matchThese['date_start']));
            }
            if (!empty($matchThese['date_end'])) {
                $matchThese['date_end'] = date("d-m-Y", strtotime($request->get('date_end')));
            }
        } else {
            if ($pagination == true) {
                $count = $leaves->count();
                $leaves = $leaves->paginate($per_page);
            } else {
                $count = $leaves->count();
                $leaves = $leaves->distinct()->get();
            }
        }
        if ($request->ajax()) {
            return [
                'html' => view('laralum/attendance/_leave', ['leaves' => $leaves, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $staffMatchThese, $staffDepartmentMatchThese)])->render()
            ];
        }

        return view('laralum.attendance.leave', compact('leaves', 'count'));
    }


    public function printAttendanceLeaves(Request $request, $date = null)
    {
        Laralum::permissionToAccess('admin.attendance.list');

        $matchThese = [];
        $staffDepartmentMatchThese = [];
        $staffMatchThese = [];

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $search = false;
        $option_ar = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = 'Name';
                $staffMatchThese['name'] = $search_data['name'];
            }

            if (!empty($search_data['department'])) {
                $search = true;
                $option_ar[] = 'Department';
                $staffDepartmentMatchThese['title'] = $search_data['department'];
            }

            if (!empty($search_data['date_start'])) {
                $search = true;
                $option_ar[] = 'Start Date';
                $matchThese['date_start'] = date("Y-m-d", strtotime($search_data['date_start']));
            }

            if (!empty($search_data['date_end'])) {
                $search = true;
                $option_ar[] = 'End Date';
                $matchThese['date_end'] = date("Y-m-d", strtotime($search_data['date_end']));
            }
        }

        $leaves = Leave::select('leaves.*')->orderBy('created_at', 'DESC');

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        //Define how many items we want to be visible in each page
        $perPage = $request->get("per_page") ? $request->get("per_page") : 10;
        if ($search == true) {
            $leaves = Leave::select('leaves.*')->join('staff', 'staff.id', '=', 'leaves.user_id')->join('staff_departments', 'staff_departments.id', 'staff.department')->where(function ($query) use ($matchThese, $staffDepartmentMatchThese, $staffMatchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where('leaves.' . $key, 'like', "%$match%");
                }

                foreach ($staffMatchThese as $key => $match) {
                    $query->where('staff.' . $key, 'like', "%$match%");
                }
                foreach ($staffDepartmentMatchThese as $key => $match) {
                    $query->where('staff_departments.' . $key, 'like', "%$match%");
                }
            })
                ->orderBy('leaves.created_at', 'DESC');

            $count = $leaves->count();
            $leaves = $leaves->distinct()->get();

            if (!empty($staffDepartmentMatchThese['title'])) {
                $staffDepartmentMatchThese['department'] = $staffDepartmentMatchThese['title'];
            }

            if (!empty($matchThese['date_start'])) {
                $matchThese['date_start'] = date("d-m-Y", strtotime($matchThese['date_start']));
            }
            if (!empty($matchThese['date_end'])) {
                $matchThese['date_end'] = date("d-m-Y", strtotime($request->get('date_end')));
            }
        } else {
            if ($pagination == true) {
                $count = $leaves->count();
                $leaves = $leaves->paginate($per_page);
            } else {
                $count = $leaves->count();
                $leaves = $leaves->distinct()->get();
            }
        }

        $print = true;
        return view('laralum.attendance.print-attendance-leaves', compact('leaves', 'date', 'print'));

    }

    public function store(Request $request)
    {

        $rules = Attendance::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $date = $request->get('date_in');
            if ($date == null)
                $date = date('Y-m-d');

            if ($request->get('date_in') > date("Y-m-d") && $request->get("status") != Attendance::STATUS_LEAVE) {
                return redirect()->back()->withInput()->with(['error' => 'You can not mark attendance of future dates.']);
            }

            $attendance = Attendance::where('user_id', $request->get('user_id'))->where('date_in', $date)->first();

            if ($attendance == null)
                $attendance = new Attendance();

            $data = $request->all();
            if ($attendance->setData($data)) {
                $attendance->save();
                $label = $attendance->getStatusLabelOptions($attendance->status);

                if ($attendance->status == Attendance::STATUS_LEAVE) {
                    $label .= ' <i class="fa fa-question" style="cursor:pointer;" id="comment_' . $attendance->user_id . '"title="' . $attendance->comment . '"></i>';
                }

                $label .= '<input type="hidden" id="time_in_val_' . $attendance->user_id . '" value=' . $attendance->time_in . '>';
                $label .= '<input type="hidden" id="time_out_val_' . $attendance->user_id . '" value=' . $attendance->time_out . '>';

                $label .= '<input type="hidden" id="selected_state_' . $attendance->user_id . '" value=' . $attendance->status . '>';
                if (Laralum::loggedInUser()->hasPermission('attendance.edit')) {
                    $label .= ' <i id="edit_' . $attendance->user_id . '" class="fa fa-edit hover"></i>';
                }

                $result['status'] = $label;
                return $result;
                /*return redirect()->route('Laralum::attendances')->with('success', 'Attendance marked successfully.');*/
            } else {
                $result['status'] = $attendance->getStatusLabelOptions(Attendance::getStatusLabelOptions());
                return $result;
                /*return redirect()->route('Laralum::attendances')->with('error', 'Something went wrong. Please try again later.');*/
            }

        } catch (\Exception $e) {
            Log::error("Failed to add the attendance, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::attendances')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * search attendance from column
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {

        $attendances = Attendance::getAttendanceFromColumn('date_in', $request->get('date_in'));
        $html = View::make('laralum.attendance._list', compact('attendances'))->render();

        return Response::json(['status' => 'success', 'html' => $html]);

    }

    /*
     * Add leave for an Employee for dates
     * @param $id
     * @return @view
     */
    public function addLeave($id = null, $date = null)
    {
        $user = new Staff();
        if ($id != null)
            $user = Staff::find($id);

        if ($date == null)
            $date = date('d-m-Y');

        return view('laralum.attendance.add_leave', compact('user', 'date'));
    }


    /*
     * Save leave for an Employee for dates
     * @param Request $request & $id
     * @return @vredirect
     */
    public function saveLeave(Request $request, $id = null)
    {
        $user = Staff::find($request->get('user_id'));
        $date = $request->get('dates');
        $date_ar = array_filter(explode('to', $date));
        $date_from = date("Y-m-d H:i:s", strtotime($date_ar[0]));
        $date_to = date("Y-m-d H:i:s", strtotime($date_ar[0]));

        if (isset($date_ar[1])) {
            $date_to = date("Y-m-d H:i:s", strtotime($date_ar[1]));
        }

        $leave = new Leave();
        $leave->user_id = $user->id;
        $leave->date_start = $date_from;
        $leave->date_end = $date_to;
        $leave->type = Leave::TYPE_LEAVE;
        $leave->comment = $request->comment;

        $check = $leave->check($date_ar);

        if($check == false) {
            return redirect()->back()->with('error', 'Please choose another dates, leaves for these dates already exists.');
        }

        $leave->save();
        $date_range_ar = Settings::createDateRangeArray($date_from, $date_to);

        if ($date_from != null && $user != null) {
            $date_range_ar_string = implode(',', $date_range_ar);
            $comment = $request->get('comment');
            $leave->saveLeave($date_range_ar_string, $comment);
            return redirect()->route('Laralum::attendance.leaves')->with('success', 'Successfully Added leaves for: ' . $user->name);
            /*
            return redirect()->route('Laralum::attendance.create.date', ['date' => $date])->with('success', 'Successfully Added leaves for: ' . $user->name);*/
        }

        return redirect()->route('Laralum::attendance.add_leave_any')->with('error', 'Something went wrong!!!');
    }


    public function deleteLeave(Request $request, $id)
    {
        $leave = Leave::find($id);

        if ($leave) {
            $leave->customDelete();
            return redirect('admin/leaves')->with('success', 'Successfully Deleted Leave.');
        }

        abort(404);
    }


    /*
    * Add leave for an Employee for dates
    * @param $id
    * @return @view
    */
    public function editLeave($id = null)
    {
        $leave = Leave::find($id);

        return view('laralum.attendance.edit_leave', compact('leave'));
    }

    public function editLeaveStore(Request $request, $id)
    {
        $user = Staff::find($request->get('user_id'));
        $date = $request->get('dates');

        $date_ar = array_filter(explode('to', $date));
        $date_from = $date_ar[0];
        $date_to = $date_ar[0];

        if (isset($date_ar[1])) {
            $date_to = $date_ar[1];
        }

        $leave = Leave::find($id);

        $check = $leave->check($date_ar);
        if($check == false) {
            return redirect()->back()->with('error', 'Please choose another dates, leaves for these dates already exists.');
        }

        $leave->date_start = date("Y-m-d H:i:s", strtotime($date_from));
        $leave->date_end = date("Y-m-d H:i:s", strtotime($date_to));
        $leave->type = Leave::TYPE_LEAVE;
        $leave->comment = $request->comment;
        $leave->save();

        $date_range_ar = Settings::createDateRangeArray($date_from, $date_to);

        if ($date_from != null && $user != null) {

            $date_range_ar_string = implode(',', $date_range_ar);
            $comment = $request->get('comment');
            $leave->saveLeave($date_range_ar_string, $comment);

            return redirect()->route('Laralum::attendance.leaves')->with('success', 'Successfully Added leaves for: ' . $user->name);
            /*
            return redirect()->route('Laralum::attendance.create.date', ['date' => $date])->with('success', 'Successfully Added leaves for: ' . $user->name);*/
        }

        return redirect()->route('Laralum::attendance.add_leave_any')->with('error', 'Something went wrong!!!');
    }

    public function export(Request $request, $type, $per_page = 10, $page = 1)
    {
        //$users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');
        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $serachname = $search_data['name'];
                $search = true;
                $option_ar[] = 'Name';
                $staffMatchThese['name'] = $search_data['name'];
                $users = Staff::select('staff.*')->where('name', 'like', "%$serachname%")->orderBy('staff.created_at', 'DESC');
            }
            else{
                $users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');
            }
            
        }
        else{
            $users = Staff::select('staff.*')->orderBy('staff.created_at', 'DESC');
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d", strtotime("-7 days"));


        $date = $request->date;
        if ($date != "") {
            $date_arr = explode('to', $date);
            $from_date = $date_arr[0];
            if (isset($date_arr[1])) {
                $to_date = $date_arr[1];
            } else {
                $to_date = date("Y-m-d", strtotime($from_date . " +7 days"));
            }
        }


        $count = $users->count();
        $users = $users->get();
        $range = $date;
        $dates = Settings::createDateRangeArray($from_date, $to_date);

        $attendance = [
            'name'
        ];
        $attendance_dates = [];
        $search_data = [];

        foreach ($dates as $date) {
            $attendance_dates[] = date('d-m-Y', strtotime($date));
            if (!empty($request->get('status_' . $date))) {
                    $option_ar[] = "Status";
                    $search = true;
                    $search_data['status_' . $date] = $request->get('status_' . $date);
            }
        }

        $attendances[] = array_merge($attendance, $attendance_dates, $search_data);


        foreach ($users as $user) {
            $attendance = [
                $user->name
            ];
            $attendance_dates = [];
            foreach ($dates as $date) {
                $attendance_dates[] = strip_tags($user->attendance($date, ''));
            }

            $attendances[] = array_merge($attendance, $attendance_dates, $search_data);;
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Attendances', function ($excel) use ($attendances) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Attendances');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($attendances) {
                $sheet->fromArray($attendances, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $attendances));
            return $pdf->download('attendances.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function listLeaves(Request $request, $id = null)
    {
        return true;
    }

    public function ajaxUpdate(Request $request)
    {

        //return $request->all();
        Laralum::permissionToAccess('admin.attendance.list');
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
        $search_data = [];
        if (!empty($request->has('dates'))) {
            $range = $request->get('dates');
            $dates_ar = array_filter(explode('to', $request->get('dates')));
            $from_date = $dates_ar[0];
            $to_date = isset($dates_ar[1]) ? $dates_ar[1] : date("Y-m-d");
            $dates = Settings::createDateRangeArray($from_date, $to_date);
            foreach ($dates as $date) {
                if (!empty($request->get('status_' . $date))) {
                    $option_ar[] = "Status";
                    $search = true;
                    $search_data['status_' . $date] = $request->get('status_' . $date);
                }
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
            $models = Staff::select('staff.*');
            if (!empty($matchThese)) {
                $models = $models->where(function ($query) use ($matchThese) {
                    foreach ($matchThese as $key => $match) {
                        $query->where($key, 'like', "%$match%");
                    }
                });
            }
            $models = $models->orderBy('staff.created_at', 'DESC');
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
            'html' => view('laralum/attendance/_list', ['dates' => $dates, 'range' => $range, 'users' => $models, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $search_data), 'search_status'])->render()
        ];
    }

    public function exportLeaves(Request $request, $type, $per_page = 10, $page = 1)
    {
        Laralum::permissionToAccess('admin.attendance.list');

        $matchThese = [];
        $staffDepartmentMatchThese = [];
        $staffMatchThese = [];

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $search = false;
        $option_ar = [];


        if ($request->s && $request->s != 'null') {
            $search_data = json_decode($request->s, true);

            if (!empty($search_data['name'])) {
                $search = true;
                $option_ar[] = 'Name';
                $staffMatchThese['name'] = $search_data['name'];
            }

            if (!empty($search_data['department'])) {
                $search = true;
                $option_ar[] = 'Department';
                $staffDepartmentMatchThese['title'] = $search_data['department'];
            }

            if (!empty($search_data['date_start'])) {
                $search = true;
                $option_ar[] = 'Start Date';
                $matchThese['date_start'] = date("Y-m-d", strtotime($search_data['date_start']));
            }

            if (!empty($search_data['date_end'])) {
                $search = true;
                $option_ar[] = 'End Date';
                $matchThese['date_end'] = date("Y-m-d", strtotime($search_data['date_end']));
            }
        }

        $leaves = Leave::select('leaves.*')->orderBy('created_at', 'DESC');

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        //Define how many items we want to be visible in each page
        $perPage = $request->get("per_page") ? $request->get("per_page") : 10;
        if ($search == true) {
            $leaves = Leave::select('leaves.*')->join('staff', 'staff.id', '=', 'leaves.user_id')->join('staff_departments', 'staff_departments.id', 'staff.department')->where(function ($query) use ($matchThese, $staffDepartmentMatchThese, $staffMatchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where('leaves.' . $key, 'like', "%$match%");
                }

                foreach ($staffMatchThese as $key => $match) {
                    $query->where('staff.' . $key, 'like', "%$match%");
                }
                foreach ($staffDepartmentMatchThese as $key => $match) {
                    $query->where('staff_departments.' . $key, 'like', "%$match%");
                }
            })
                ->orderBy('leaves.created_at', 'DESC');

            $count = $leaves->count();
            $leaves = $leaves->distinct()->get();

            if (!empty($staffDepartmentMatchThese['title'])) {
                $staffDepartmentMatchThese['department'] = $staffDepartmentMatchThese['title'];
            }

            if (!empty($matchThese['date_start'])) {
                $matchThese['date_start'] = date("d-m-Y", strtotime($matchThese['date_start']));
            }
            if (!empty($matchThese['date_end'])) {
                $matchThese['date_end'] = date("d-m-Y", strtotime($request->get('date_end')));
            }
        } else {
            if ($pagination == true) {
                $count = $leaves->count();
                $leaves = $leaves->paginate($per_page);
            } else {
                $count = $leaves->count();
                $leaves = $leaves->distinct()->get();
            }
        }

        $attendances_array[] = [
            'Employee',
            'Department',
            'Start Date',
            'End Date',
            'Comments'
        ];

        foreach ($leaves as $leave) {
            $attendances_array[] = [
                $leave->user->name,
                $leave->user->staffDepartment->title ,
                $leave->date_start_date,
                $leave->date_end_date,
                $leave->comment,
            ];
        }

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Leaves', function ($excel) use ($attendances_array) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Leaves List');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($attendances_array) {
                $sheet->fromArray($attendances_array, null, 'A1', false, false);
            });

        });
        if ($type == Settings::EXPORT_CSV) {
            $excel->download('csv');
        } elseif ($type == Settings::EXPORT_EXCEL) {
            $excel->download('xls');
        } else {
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $attendances_array));
            return $pdf->download();
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
