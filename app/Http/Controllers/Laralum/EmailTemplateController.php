<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use JDT\LaravelEmailTemplates\Entities\EmailTemplate;
use PDF;

class EmailTemplateController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $models = \App\EmailTemplate::select('*');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $models->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        if ($request->ajax()) {

            return [
                'html' => view('laralum/group-items/_list', ['group_items' => $group_items, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseN)])->render()
            ];

        }
        return view('laralum.email_templates.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $model = new \App\EmailTemplate();
        return view('laralum.email_templates.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        //
        $model = new \App\EmailTemplate();

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model = \App\EmailTemplate::where('event_id', $request->get('event_id'))->first();

        if ($model == null) {
            $model = new \App\EmailTemplate();
        }

        $model->setData($request);

        if($model->save()) {
            return redirect('/admin/email-templates')->with('success', 'Successfully Added Email Template');
        }

        return redirect()->back()->withInput()->with('error', 'Something went wrong');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $model = EmailTemplate::find($id);
        return view('laralum.email_templates.view', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $model = EmailTemplate::find($id);
        return view('laralum.email_templates.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $model = \App\EmailTemplate::find($id);

        $validator = \Validator::make($request->all(), $model->rules());

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $model->setData($request);

        if($model->save()) {
            return redirect('/admin/email-templates')->with('success', 'Successfully Updated Email Template');
        }

        return redirect()->back()->withInput()->with('error', 'Something went wrong');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $model = \App\EmailTemplate::find($id);

        if ($model->delete()) {
            return redirect('admin/email-templates')->with('success', 'Successfully Deleted Template');
        }

        return redirect()->back()->with('error', 'Something went wrong!!!');
    }

    public function getEvents(Request $request)
    {
        $group = $request->get('group_id');
        return \App\EmailTemplate::getEventOptions(null, $group);
    }

    public function printEmailTemplate(Request $request)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $models = \App\EmailTemplate::select('*');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $models->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        return view('laralum/email_templates/print_email_templates', [
            'models' => $models,
            'print' => true

        ]);
    }

    public function exportEmailTemplate(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.admin_settings.email_templates');
        $models = \App\EmailTemplate::select('*');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $models->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $models = $models->paginate($per_page);
        } else {
            $models = $models->get();
        }

        $all_ar[] = [
            'Event',
            'Subject',
            'Description'
        ];

        foreach ($models as $model)
        {
            $all_ar[] = [
                $model->getEvent(),
                $model->subject,
                strip_tags($model->content)
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Email Templates', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Email Templates');

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
            return $pdf->download('email_templates.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
