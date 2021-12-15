<?php

namespace App\Http\Controllers\Laralum;

use App\Http\Controllers\Controller;
use App\InventoryGroup;
use App\InventoryGroupItem;
use App\Settings;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class InventoryGroupItemController extends Controller
{
    /**
     * group listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_group_items');
        $matchThese = [];
        $search = false;
        $option_ar = [];
        $stock_items = [];
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['title'] = $request->get('filter_name');
        }

        $group_title = null;
        if ($request->has('filter_group_title') && $request->get('filter_group_title') != "") {
            $option_ar[] = "Group Name";
            $search = true;
            $group_title = $request->get('filter_group_title');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($search == true) {
            $group_items = InventoryGroupItem::select('inventory_group_items.*')->join("inventory_groups", "inventory_groups.id", '=', 'inventory_group_items.group_id')
                ->where(function ($query) use ($matchThese, $group_title) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('inventory_group_items.' . $key, 'like', "%$match%");
                    }
                    $query->where('inventory_groups.title', 'like', "%$group_title%");
                })->orderBy('inventory_group_items.created_at', 'DESC');

        } else {
            $group_items = InventoryGroupItem::select('inventory_group_items.*')->orderBy('inventory_group_items.created_at', 'DESC');
        }

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        $count = $group_items->count();
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($pagination == true) {
            $group_items = $group_items->paginate($per_page);
        } else {
            $group_items = $group_items->get();
        }

        return view('laralum.group-items.index', compact('group_items', 'count', 'search', 'error'));
    }

    /**
     * group details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.inventory_group_items');
        $group = InventoryGroupItem::find($id);

        return view('laralum.group-items.view', compact('group'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.inventory_group_items');

        # Find the group
        $row = InventoryGroupItem::findOrFail($id);/*
        \Session::put('group_id', $id);*/

        # Get all the data
        $data_index = 'group-items';
        /*require('Data/Create/Get.php');*/

        # Return the view
        return view('laralum/group-items/edit', [
            'row' => $row,
            /* 'fields'    =>  $fields,
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
        Laralum::permissionToAccess('admin.inventory_group_items');

        # Find the row
        $group = InventoryGroupItem::findOrFail($id);

        try {

            if ($group->setData($request)) {
                $group->save();
                return redirect()->route('Laralum::group-items')->with('success', 'Group edited successfully.');
            } else {
                return redirect()->route('Laralum::group-items')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the group, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::group-items')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add group for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.inventory_group_items');
        # Get all the data

        return view('laralum.group-items.create',
            [
                /*  'fields'        =>  $fields,
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
        Laralum::permissionToAccess('admin.inventory_group_items');
        $rules = InventoryGroupItem::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $groupItem = new InventoryGroupItem();

            if ($groupItem->setData($request)) {
                $groupItem->save();
                return redirect()->route('Laralum::group-items')->with('success', 'Group Item added successfully.');
            } else {
                return redirect()->route('Laralum::group-items')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the Group Item, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::group-items')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.inventory_group_items');

        # Select Group
        $group = InventoryGroupItem::findOrFail($id);
        # Delete Group
        $group->delete();
        # Redirect the admin
        return redirect()->route('Laralum::group-items')->with('success', trans('laralum.msg_group_item_deleted'));

    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_group_items');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->has('title') && $request->get('title') != "") {
            $option_ar[] = "Item Name";
            $search = true;
            $matchThese['title'] = $request->get('title');
        }
        $matchTheseN = [];
        $group_title = null;
        if ($request->has('group') && $request->get('group') != "") {
            $option_ar[] = "Group Name";
            $search = true;
            $matchTheseN['group'] = $request->get('group');
            $group_title = $request->get('group');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $group_items = InventoryGroupItem::select('inventory_group_items.*')->orderBy('inventory_group_items.created_at', 'DESC');
        if ($search == true) {
            $group_items = InventoryGroupItem::select('inventory_group_items.*')->join("inventory_groups", "inventory_groups.id", '=', 'inventory_group_items.group_id')
                ->where(function ($query) use ($matchThese, $group_title) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('inventory_group_items.' . $key, 'like', "%$match%");
                    }
                    $query->where('inventory_groups.title', 'like', "%$group_title%");
                })->orderBy('inventory_group_items.created_at', 'DESC');
            $group_items = $group_items->get();
            $count = $group_items->count();
        } else {
            $count = $group_items->count();
            if ($pagination == true) {
                $group_items = $group_items->paginate($per_page);
            } else {
                $group_items = $group_items->get();
            }
        }

        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/group-items/_list', ['group_items' => $group_items, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseN)])->render()
        ];
    }

    public function printGroupsItems(Request $request)
    {
        Laralum::permissionToAccess('admin.inventory_group_items');

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
                $option_ar[] = "Item Name";
                $search = true;
                $matchThese['title'] = $search_data['title'];
            }
            $matchTheseN = [];
            $group_title = null;
            if (!empty($search_data['group'])) {
                $option_ar[] = "Group Name";
                $search = true;
                $matchTheseN['group'] = $search_data['group'];
                $group_title = $search_data['group'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $group_items = InventoryGroupItem::select('inventory_group_items.*')->orderBy('inventory_group_items.created_at', 'DESC');
        if ($search == true) {
            $group_items = InventoryGroupItem::select('inventory_group_items.*')->join("inventory_groups", "inventory_groups.id", '=', 'inventory_group_items.group_id')
                ->where(function ($query) use ($matchThese, $group_title) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('inventory_group_items.' . $key, 'like', "%$match%");
                    }
                    $query->where('inventory_groups.title', 'like', "%$group_title%");
                })->orderBy('inventory_group_items.created_at', 'DESC');
            $group_items = $group_items->get();
            $count = $group_items->count();
        } else {
            $count = $group_items->count();
            if ($pagination == true) {
                $group_items = $group_items->paginate($per_page);
            } else {
                $group_items = $group_items->get();
            }
        }

        return view('laralum/group-items/print_groups_items', [
            'group_items' => $group_items,
            'count' => $count,
            'print' => true,
            'search' => $search,
            'error' => $error
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
                    $group = InventoryGroup::where('title' , $row_ar['group'])->first();
                    if ($group) {
                        InventoryGroupItem::importData($row_ar, $group->id);
                    }
                }
            });
        }
        return redirect()->back();
    }


    public function exportGroupsItems(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.inventory_group_items');

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
                $option_ar[] = "Item Name";
                $search = true;
                $matchThese['title'] = $search_data['title'];
            }
            $matchTheseN = [];
            $group_title = null;
            if (!empty($search_data['group'])) {
                $option_ar[] = "Group Name";
                $search = true;
                $matchTheseN['group'] = $search_data['group'];
                $group_title = $search_data['group'];
            }
        }

        $options = implode(", ", $option_ar);

        $error = "Entered " . $options . " is not valid,
make sure that you are entering valid " . $options . " 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $group_items = InventoryGroupItem::select('inventory_group_items.*')->orderBy('inventory_group_items.created_at', 'DESC');
        if ($search == true) {
            $group_items = InventoryGroupItem::select('inventory_group_items.*')->join("inventory_groups", "inventory_groups.id", '=', 'inventory_group_items.group_id')
                ->where(function ($query) use ($matchThese, $group_title) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('inventory_group_items.' . $key, 'like', "%$match%");
                    }
                    $query->where('inventory_groups.title', 'like', "%$group_title%");
                })->orderBy('inventory_group_items.created_at', 'DESC');
            $group_items = $group_items->get();
            $count = $group_items->count();
        } else {
            $count = $group_items->count();
            if ($pagination == true) {
                $group_items = $group_items->paginate($per_page);
            } else {
                $group_items = $group_items->get();
            }
        }

        $all_ar[] = [
            'Title',
            'Group',
        ];

        foreach ($group_items as $group)
        {
            $all_ar[] = [
                $group->title,
                @$group->group->title,
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Group Items', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Group Items');

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
            return $pdf->download('groups_items_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }
}
