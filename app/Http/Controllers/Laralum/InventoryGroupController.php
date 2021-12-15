<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\InventoryGroup;
use App\Settings;
use Illuminate\Http\Request;
use PDF;
use App\User;

class InventoryGroupController extends Controller
{
    //
    /**
     * group listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_groups');

        if ($request->get("title") != null) {
            $group = InventoryGroup::where('title', $request->get("title"));

            $group->update([
                'group' => $request->get("title")
            ]);
        }
        $user = \Auth::user();
        $admin = $user->Admin();

        $groups = InventoryGroup::select('*');

        if (!\Auth::user()->isAdmin()) {
            $groups = $groups->where('created_by', \Auth::user()->id)->orWhere('created_by', $admin);
        }
        
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $groups->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $groups = $groups->paginate($per_page);
        } else {
            $groups = $groups->get();
        }

        return view('laralum.groups.index', compact('groups', 'count'));
    }

    /**
     * group details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.inventory_groups');
        $group = Group::find($id);

        return view('laralum.groups.view', compact('group'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.inventory_groups');

        # Find the group
        $model = InventoryGroup::findOrFail($id);
        /*\Session::put('group_id', $id);

        # Get all the data
        $data_index = 'groups';
        require('Data/Create/Get.php');*/

        # Return the view
        return view('laralum/groups/edit', [
            'model' => $model
            /*'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
            'relations' =>  $relations,*/
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.inventory_groups');

        # Find the row
        $group = InventoryGroup::findOrFail($id);

        try {

            if ($group->setData($request)) {
                $group->save();
                return redirect()->route('Laralum::groups')->with('success', 'Group edited successfully.');
            } else {
                return redirect()->route('Laralum::groups')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the group, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::groups')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add group for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.inventory_groups');
        # Get all the data
        /*   $data_index = 'groups';
           require('Data/Create/Get.php');*/

        return view('laralum.groups.create',
            [/*
                'fields'        =>  $fields,
                'confirmed'     =>  $confirmed,
                'encrypted'     =>  $encrypted,
                'hashed'        =>  $hashed,
                'masked'        =>  $masked,
                'table'         =>  $table,
                'code'          =>  $code,
                'wysiwyg'       =>  $wysiwyg,
                'relations'     =>  $relations,*/
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_groups');
        $rules = InventoryGroup::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $group = new InventoryGroup();

            if ($group->setData($request)) {
                $group->save();
                return redirect()->route('Laralum::groups')->with('success', 'Group added successfully.');
            } else {
                return redirect()->route('Laralum::groups')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback group, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::groups')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.inventory_groups');

        # Select Group
        $group = InventoryGroup::findOrFail($id);
        # Delete Group
        $group->customDelete();
        # Redirect the admin
        return redirect()->route('Laralum::groups')->with('success', trans('laralum.msg_group_deleted'));

    }

    public function printGroups(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_groups');

        if ($request->get("title") != null) {
            $group = InventoryGroup::where('title', $request->get("title"));

            $group->update([
                'group' => $request->get("title")
            ]);
        }
        $user = \Auth::user();
        $admin = $user->Admin();

        $groups = InventoryGroup::select('*');

        if (!\Auth::user()->isAdmin()) {
            $groups = $groups->where('created_by', \Auth::user()->id)->orWhere('created_by', $admin);
        }

        
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $groups->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $groups = $groups->paginate($per_page);
        } else {
            $groups = $groups->get();
        }


        return view('laralum/groups/print_groups', [
            'groups' => $groups,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportGroups(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.inventory_groups');

        if ($request->get("title") != null) {
            $group = InventoryGroup::where('title', $request->get("title"));

            $group->update([
                'group' => $request->get("title")
            ]);
        }
        $user = \Auth::user();
        $admin = $user->Admin();

        $groups = InventoryGroup::select('*');

        if (!\Auth::user()->isAdmin()) {
            $groups = $groups->where('created_by', \Auth::user()->id)->orWhere('created_by', $admin);
        }
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $groups->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $groups = $groups->paginate($per_page);
        } else {
            $groups = $groups->get();
        }

        $all_ar[] = [
            'Title',
            'Description',
        ];

        foreach ($groups as $group)
        {
            $all_ar[] = [
                $group->title,
                $group->description,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Groups', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Groups');

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
            return $pdf->download('groups_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
