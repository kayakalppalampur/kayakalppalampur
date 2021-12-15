<?php

namespace App\Http\Controllers\Laralum;

use App\Issue;
use App\IssueReply;
use App\Notification;
use App\Settings;
use App\User;
use Hamcrest\Core\Is;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class IssueController extends Controller
{
    //

    /**
     * issue listing
     * @return View
     */
    public function index(Request $request)
    {
        $issues = Issue::where('type', Issue::TYPE_ISSUE)->orderBy('issues.created_at', 'DESC')->select('*');

        if (!\Auth::user()->isAdmin()) {
            $issues = $issues->where('created_by', \Auth::user()->id)->orderBy('issues.created_at', 'DESC');
        }

        $title = "Issues";
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $issues = $issues->paginate($per_page);
        } else {
            $issues = $issues->get();
        }

        return view('laralum.issues.index', compact('issues', 'title'));
    }

    /**
     * query listing
     * @return View
     */
    public function queries(Request $request)
    {
        $issues = Issue::where('type', Issue::TYPE_QUERY)->select('*');
        /*
                if(!\Auth::user()->isAdmin()) {
                    $issues = $issues->where('created_by', \Auth::user()->id);
                }*/
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;

        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $issues = $issues->orderBy('created_at', "DESC")->paginate($per_page);
        } else {
            $issues = $issues->orderBy('created_at', "DESC")->get();
        }
        $query = true;
        $title = "Queries";
        Notification::updateNotification(Issue::class);
        return view('laralum.issues.index', compact('issues', 'query', 'title'));
    }

    /**
     * issue details with replies
     * @return View
     */
    public function view($id)
    {
        $issue = Issue::find($id);
        $title = "Query Details";
        if ($issue->type == Issue::TYPE_ISSUE)
            $title = "Issue Details";
        return view('laralum.issues.view', compact('issue', 'title'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('issues.edit');

        # Find the issue
        $row = Issue::findOrFail($id);

        # Get all the data
        $data_index = 'issues';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/issues/edit', [
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
        Laralum::permissionToAccess('issues.edit');

        # Find the row
        $issue = Issue::findOrFail($id);

        try {

            if ($issue->setData($request)) {
                $issue->save();
                return redirect()->route('Laralum::issues')->with('success', 'Issue edited successfully.');
            } else {
                return redirect()->route('Laralum::issues')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the issue, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::issues')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add issue for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        # Get all the data
        $data_index = 'issues';
        require('Data/Create/Get.php');

        return view('laralum.issues.create',
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

        $rules = Issue::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $issue = new Issue();

            if ($issue->setData($request)) {
                $issue->save();
                return redirect()->route('Laralum::issues')->with('success', 'Issue added successfully.');
            } else {
                return redirect()->route('Laralum::issues')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the issue, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::issues')->with('error', 'Something went wrong. Please try again later.');
        }
    }


    /**
     * search issue from column
     * @param Request $request
     * @return mixed
     */
    public function changeStatus(Request $request, $id)
    {
        # Check permissions
        Laralum::permissionToAccess('issue.change_status');
        $issue = Issue::find($id);

        // try{
        $status = $request->get('status');
        if ($issue->update([
            'status' => $status
        ])
        ) {
            $options = Issue::getOptionList($issue->status);

            $label = $issue->getStatusLabelOptions($issue->status);

            if (Laralum::loggedInUser()->hasPermission('issue.change_status')) {
                $label .= '<input type="hidden" id="selected_state_' . $issue->id . '" value="' . $issue->status . '">
                         <i id="edit_' . $issue->id . '" class="fa fa-edit edit_span"></i>
                            <div id="change_status_div_' . $issue->id . '" style="display:none;">
                            <form id="issue_form_' . $issue->id . '" action="' . route('Laralum::issue.change_status', ['issue_id' => $issue->id]) . '">
                                <select id="change_status_option_' . $issue->id . '">' . $options . '</select>
                                </form>
                            </div>';
            }

            $result['status'] = $label;
            /*return redirect()->route('Laralum::attendances')->with('success', 'Attendance marked successfully.');*/
        }
        return $result;

        /*}catch(\Exception $e){

            \Log::error("Failed to add the attendance, possible causes: ".$e->getMessage());
            print_r($e->getMessage());exit;
            return redirect()->route('Laralum::attendances')->with('error', 'Something went wrong. Please try again later.');
        }*/

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('issues.delete');

        # Select Issue
        $issue = Issue::findOrFail($id);
        $type = $issue->type;
        # Delete Issue
        $issue->delete();

        # Redirect the admin
        if ($type == Issue::TYPE_ISSUE)
            return redirect()->route('Laralum::issues')->with('success', trans('laralum.msg_issue_deleted'));

        return redirect()->route('Laralum::queries')->with('success', trans('laralum.msg_query_deleted'));
    }

    /*
     * save reply of issue
     * @return list of replies
     * */
    public function reply(Request $request, $id)
    {
        # Select Issue
        $issue = Issue::findOrFail($id);

        $validator = \Validator::make($request->all(), ['message' => 'required']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $reply = IssueReply::create([
            'issue_id' => $id,
            'message' => $request->get('message'),
            'created_by' => \Auth::user()->id
        ]);
        $email = $issue->email_id;
        if ($email == null && $issue->created_by != null) {
            $user = User::find($issue->created_by);
            if ($user != null) {
                $email = $user->email;
            }
        }
        $title = "Re:" . $issue->title;
        $message_string = $reply->message;
        try {
            \Mail::send('email.reply', ['title' => $title, 'message_string' => $message_string], function ($message) use ($email, $title) {
                $message->from(env("USER_EMAIL"), 'Kayakalp');
                $message->subject($title);
                $message->to($email);
            });
        } catch (\Exception $e) {
            return redirect()->route('Laralum::issue.view', ['issue_id' => $id])->with('error', $e->getMessage());

        }
        # Redirect the admin
        return redirect()->route('Laralum::issue.view', ['issue_id' => $id])->with('success', trans('laralum.msg_saved'));
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

        if (!empty($request->has('status'))) {
            $option_ar[] = "Status";
            $search = true;
            $matchThese['status'] = $request->get('status');
        }

        if (!empty($request->has('name'))) {
            $option_ar[] = "Submitted By";
            $search = true;
            $matchThese['name'] = $request->get('name');
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

        $issues = Issue::select('issues.*')->where('type', Issue::TYPE_ISSUE)->select('*')->orderBy('issues.created_at', 'DESC');

        if ($search == true) {
            $issues = Issue::select('issues.*')->where('type', Issue::TYPE_ISSUE)->where(function ($query) use ($matchThese) {
                foreach ($matchThese as $key => $match) {
                    $query->where($key, 'like', "%$match%");
                }
            })
                ->orderBy('issues.created_at', 'DESC');

            $count = $issues->count();
            $issues = $issues->get();
        } else {
            $count = $issues->count();
            if ($pagination == true) {
                $issues = $issues->paginate($per_page);
            } else {
                $issues = $issues->get();
            }
        }
        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/issues/_list', ['issues' => $issues, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => $matchThese])->render()
        ];

    }

    public function printIssues(Request $request)
    {
        $issues = Issue::where('type', Issue::TYPE_ISSUE)->orderBy('issues.created_at', 'DESC')->select('*');

        if (!\Auth::user()->isAdmin()) {
            $issues = $issues->where('created_by', \Auth::user()->id)->orderBy('issues.created_at', 'DESC');
        }

        $title = "Issues";
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $issues = $issues->paginate($per_page);
        } else {
            $issues = $issues->get();
        }


        return view('laralum/issues/print_issues', [
            'issues' => $issues,
            'title' => $title,
            'print' => true

        ]);
    }

    public function exportIssues(Request $request, $type)
    {
        $issues = Issue::where('type', Issue::TYPE_ISSUE)->orderBy('issues.created_at', 'DESC')->select('*');

        if (!\Auth::user()->isAdmin()) {
            $issues = $issues->where('created_by', \Auth::user()->id)->orderBy('issues.created_at', 'DESC');
        }

        $title = "Issues";
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        if ($pagination == true) {
            $issues = $issues->paginate($per_page);
        } else {
            $issues = $issues->get();
        }


        $all_ar[] = [
            'Title',
            'Description',
            'Status',
            'Submitted By',
            'Submitted on',
        ];
        foreach ($issues as $issue) {
            $lab_tests_array[] = [
                $issue->title,
                $issue->description,
                \App\Issue::getStatusOptions($issue->status),
                $issue->name,
                $issue->created_at != null ? $issue->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString() : ""
            ];
        }

        //echo '<pre>';print_r($kitchen_items_array);exit;

        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('issues', function ($excel) use ($all_ar) {

            $excel->setTitle('Issues');

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
            // $excel->download('pdf');
            $pdf = \PDF::loadView('booking.pdf', array('data' => $all_ar));
            return $pdf->download('issues.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
