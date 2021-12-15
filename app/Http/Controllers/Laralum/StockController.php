<?php

namespace App\Http\Controllers\Laralum;

use App\ItemQuantityLog;
use App\Settings;
use App\Stock;
use App\StockItemRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;

class StockController extends Controller
{
    //
    /**
     * stock listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('admin.stock');

        $matchThese = [];
        $search = false;
        $option_ar = [];
        $stock_items = [];
        if ($request->has('filter_name') && $request->get('filter_name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['name'] = $request->get('filter_name');
        }

        $product_name = null;
        if ($request->has('filter_product_name') && $request->get('filter_product_name') != "") {
            $option_ar[] = "Product Name";
            $search = true;
            $product_name = $request->get('filter_product_name');
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
            $stock_items = Stock::select('stock.*')->leftJoin("kitchen_items", "kitchen_items.id", '=', 'stock.product_id')
                ->where(function ($query) use ($matchThese, $product_name) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('stock.' . $key, 'like', "%$match%");
                    }
                    $query->where(function ($q) use ($product_name) {
                        $q->orWhereHas('group', function ($q) use ($product_name) {
                            $q->where('title', 'like', '%' . $product_name . '%');
                        })->orWhere('product_type', 'like', "%$product_name%");
                    });
                })->orderBy('stock.created_at', 'DESC');
            $count = $stock_items->count();
            if ($pagination == true) {
                $stock_items = $stock_items->paginate($per_page);
            } else {
                $stock_items = $stock_items->get();
            }

        } else {
            $stock_items = Stock::select('stock.*');
            $stock_items = $stock_items->orderBy('created_at', "DESC");
            $count = $stock_items->count();
            if ($pagination == true) {
                $stock_items = $stock_items->paginate($per_page);
            } else {
                $stock_items = $stock_items->get();
            }
        }


        return view('laralum.stock.index', compact('stock_items', 'error', 'count'));
    }

    /**
     * stock details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('admin.stock');
        $stock = Stock::find($id);

        return view('laralum.stock.view', compact('stock'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('admin.stock');

        # Find the stock
        $row = Stock::findOrFail($id);
        \Session::put('stock_id', $id);

        # Get all the data
        $data_index = 'stock';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/stock/edit', [
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
        Laralum::permissionToAccess('admin.stock');

        # Find the row
        $stock = Stock::findOrFail($id);

        try {

            if ($stock->setData($request)) {
                $stock->save();
                return redirect()->route('Laralum::stock')->with('success', 'Stock edited successfully.');
            } else {
                return redirect()->route('Laralum::stock')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the stock, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::stock')->with('error', 'Something went wrong. Please try again later.');
        }

    }

    /**
     * add stock for the staff
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        Laralum::permissionToAccess('admin.stock');

        # Get all the data
        $data_index = 'stock';
        require('Data/Create/Get.php');

        return view('laralum.stock.create',
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
        Laralum::permissionToAccess('admin.stock');
        $rules = Stock::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $stock = Stock::where('name', $request->get("name"))->first();
            if ($stock == null)
                $stock = new Stock();
            if ($stock->setData($request, true)) {
                $stock->save();

                //Save qty logs
                $log = new ItemQuantityLog();
                $log->item_id = $stock->id;
                $log->user_id = \Auth::user()->id;
                $log->action = ItemQuantityLog::ACTION_ADDED;
                $log->qty = $request->quantity;
                $log->save();

                return redirect()->route('Laralum::stock.edit', $stock->id)->with('success', 'Stock added successfully.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback question, possible causes: " . $e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }

    }

    public function addRemoveStock($id, Request $request)
    {
        Laralum::permissionToAccess('admin.stock');

        $item = Stock::find($id);

        if (empty($item)) {
            abort(404);
        }
        # Get all the data
        $data_index = 'stock_log';
        require('Data/Create/Get.php');

        return view('laralum.stock.create',
            [
                'item' => $item,
                'stock_add' => true,
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

    public function storeLog($id, Request $request)
    {
        Laralum::permissionToAccess('admin.stock');
        $item = Stock::find($id);
        if ($request->action == ItemQuantityLog::ACTION_REMOVED) {
            $rules = [
                'qty' => 'required|integer|max:' . $item->current_quantity,
                'action' => 'required|integer'
            ];
        } else {
            $rules = [
                'qty' => 'required|integer|min:1',
                'action' => 'required|integer'
            ];
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        //try {
        $stock = Stock::find($id);
        //Save qty logs
        $log = new ItemQuantityLog();
        $log->item_id = $stock->id;
        $log->user_id = \Auth::user()->id;
        $log->action = $request->action;
        $log->qty = $request->qty;
        $log->save();

        return redirect()->route('Laralum::stock')->with('success', 'Stock added successfully.');

        /* } catch (\Exception $e) {

             \Log::error("Failed to add the feedback question, possible causes: " . $e->getMessage());
             //print_r($e->getMessage());exit;
             return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
         }*/
    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.stock');

        # Select Stock
        $stock = Stock::findOrFail($id);
        # Delete Stock
        $stock->delete();
        # Redirect the admin
        return redirect()->route('Laralum::stock')->with('success', trans('laralum.msg_stock_deleted'));

    }

    public function itemRequests(Request $request)
    {
        Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')/*where('status', StockItemRequest::STATUS_PENDING)->*/
        ->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }

        return view('laralum.stock.item_requests', compact('item_requests', 'count'));
    }

    public function printItemRequests(Request $request)
    {
        Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')/*where('status', StockItemRequest::STATUS_PENDING)->*/
        ->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }
        $print = true;

        return view('laralum.stock.print_item_requests', compact('print','item_requests', 'count'));
    }

    public function exportItemRequests(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.stock_item_request');
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }

        $item_requests = StockItemRequest::has('item')/*where('status', StockItemRequest::STATUS_PENDING)->*/
        ->orderBy('created_at', "DESC");

        $count = $item_requests->count();
        if ($pagination == true) {
            $item_requests = $item_requests->paginate($per_page);
        } else {
            $item_requests = $item_requests->get();
        }

        $all_ar[] = [
            'Name',
            'Requested By',
            'Requested On',
            'Current Quantity (when requested)',
            'Required Quantity',
            'Approved Quantity',
            'Approved Date',
            'Status',
        ];

        foreach ($item_requests as $item_request)
        {
            $all_ar[] = [
                $item_request->item->name,
                $item_request->createUser->name,
                $item_request->created_at != null ? date("d-m-Y h:i a", strtotime($item_request->created_at->setTimezone(env('TIMEZONE'))->toDateTimeString())) : "",
                $item_request->status == \App\StockItemRequest::STATUS_PENDING ? $item_request->item->current_quantity : $item_request->item_qty,
                $item_request->quantity,
                $item_request->approved_qty,
                $item_request->approved_date != null ? date("d-m-Y h:i a", strtotime($item_request->approved_date)) : '',
                $item_request->getStatusOptions($item_request->status)
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Stock Items Requests', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Stock Items Requests');

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
            return $pdf->download('stock_item_requests.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

    public function destroyRequest($id)
    {   # Check permissions
        Laralum::permissionToAccess('admin.stock_item_request');

        # Select Stock
        $stock = StockItemRequest::findOrFail($id);
        # Delete Stock
        $stock->delete();
        # Redirect the admin
        return redirect()->route('Laralum::stock.item_requests')->with('success', trans('laralum.msg_item_request_deleted'));

    }

    public function approve($id)
    {
        Laralum::permissionToAccess('admin.stock_item_request');
        $item_req = StockItemRequest::find($id);

        $qty = $item_req->quantity;

        if ($item_req->quantity > $item_req->item->current_quantity) {
            $qty = $item_req->item->current_quantity;
        }

        if ($qty > 0) {
            $log = new ItemQuantityLog();
            $log->item_id = $item_req->item_id;
            $log->user_id = \Auth::user()->id;
            $log->action = ItemQuantityLog::ACTION_REMOVED;
            $log->qty = $qty;
            $log->item_request_id = $item_req->id;
            $log->save();

            $item_req->update([
                'status' => StockItemRequest::STATUS_APPROVED,
                'approved_date' => date("Y-m-d H:i:s"),
                'approved_qty' => $qty
            ]);

            return redirect()->back()->with('success', trans('laralum.msg_item_request_approved'));
        }

        return redirect()->back()->with('error', 'Please add stock to approve this request.');
    }

    public function ajaxUpdate(Request $request)
    {
        Laralum::permissionToAccess('admin.stock');

        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        $matchThese = [];
        $search = false;
        $option_ar = [];

        if ($request->has('name') && $request->get('name') != "") {
            $option_ar[] = "Name";
            $search = true;
            $matchThese['name'] = $request->get('name');
        }

        $product_name = null;
        $matchTheseN = [];
        if ($request->has('product_name') && $request->get('product_name') != "") {
            $option_ar[] = "Product Name";
            $search = true;
            $product_name = $request->get('product_name');
            //  $matchTheseN['product_name'] = $request->get('product_name');
        }

        $product_type = null;
        if ($request->has('product_type') && $request->get('product_type') != "") {
            $option_ar[] = "Product Type";
            $search = true;
            $product_type = $request->get('product_type');
            //  $matchThese['product_type'] = $request->get('product_type');
        }

        if ($request->has('quantity') && $request->get('quantity') != "") {
            $option_ar[] = "Quantity";
            $search = true;
            $matchThese['quantity'] = $request->get('quantity');
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
            $stock_items = Stock::select('stock.*')->leftJoin("kitchen_items", "kitchen_items.id", '=', 'stock.product_id')
                ->where(function ($query) use ($matchThese, $product_name, $product_type) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('stock.' . $key, 'like', "%$match%");
                    }

                    $query->where(function ($q) use ($product_type) {
                        $q->orWhereHas('group', function ($q) use ($product_type) {
                            $q->where('title', 'like', '%' . $product_type . '%');
                        })->orWhere('product_type', 'like', "%$product_type%");
                    });
                    /*$query->where(function ($query) use ($product_name, $product_type) {
                        $query->where('product_type', 'like', "%$product_type%")->orWhere('kitchen_items.name', 'like', "%$product_name%");
                    });*/
                })->orderBy('stock.created_at', 'DESC');
            $count = $stock_items->count();
            $stock_items = $stock_items->get();
            $matchThese['product_type'] = $request->get('product_type');
            $matchThese['product_name'] = $request->get('product_name');
        } else {
            $stock_items = Stock::select('stock.*');
            $stock_items = $stock_items->orderBy('created_at', "DESC");
            $count = $stock_items->count();
            if ($pagination == true) {
                $stock_items = $stock_items->paginate($per_page);
            } else {
                $stock_items = $stock_items->get();
            }
        }


        /*echo '<pre>'; print_r($matchThese['role_id']);exit;*/
        # Return the view
        return [
            'html' => view('laralum/stock/_list', ['stock_items' => $stock_items, 'count' => $count, 'error' => $error, 'search' => $search, 'search_data' => array_merge($matchThese, $matchTheseN)])->render()
        ];
    }

    public function printStock(Request $request)
    {
        Laralum::permissionToAccess('admin.stock');

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

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            $product_name = null;
            $matchTheseN = [];
            if (!empty($search_data['product_name'])) {
                $option_ar[] = "Product Name";
                $search = true;
                $product_name = $search_data['product_name'];
                //  $matchTheseN['product_name'] = $request->get('product_name');
            }

            $product_type = null;
            if (!empty($search_data['product_type'])) {
                $option_ar[] = "Product Type";
                $search = true;
                $product_type = $search_data['product_type'];
                //  $matchThese['product_type'] = $request->get('product_type');
            }

            if (!empty($search_data['quantity'])) {
                $option_ar[] = "Quantity";
                $search = true;
                $matchThese['quantity'] = $search_data['quantity'];
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
        if ($search == true) {
            $stock_items = Stock::select('stock.*')->leftJoin("kitchen_items", "kitchen_items.id", '=', 'stock.product_id')
                ->where(function ($query) use ($matchThese, $product_name, $product_type) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('stock.' . $key, 'like', "%$match%");
                    }

                    $query->where(function ($q) use ($product_type) {
                        $q->orWhereHas('group', function ($q) use ($product_type) {
                            $q->where('title', 'like', '%' . $product_type . '%');
                        })->orWhere('product_type', 'like', "%$product_type%");
                    });
                    /*$query->where(function ($query) use ($product_name, $product_type) {
                        $query->where('product_type', 'like', "%$product_type%")->orWhere('kitchen_items.name', 'like', "%$product_name%");
                    });*/
                })->orderBy('stock.created_at', 'DESC');
            $count = $stock_items->count();
            $stock_items = $stock_items->get();
            $matchThese['product_type'] = $request->get('product_type');
            $matchThese['product_name'] = $request->get('product_name');
        } else {
            $stock_items = Stock::select('stock.*');
            $stock_items = $stock_items->orderBy('created_at', "DESC");
            $count = $stock_items->count();
            if ($pagination == true) {
                $stock_items = $stock_items->paginate($per_page);
            } else {
                $stock_items = $stock_items->get();
            }
        }
        # Return the view
        return view('laralum/stock/print_stock', [
            'stock_items' => $stock_items,
            'count' => $count,
            'print' => true

        ]);
    }

    public function exportStock(Request $request, $type)
    {
        Laralum::permissionToAccess('admin.stock');

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

            if (!empty($search_data['name'])) {
                $option_ar[] = "Name";
                $search = true;
                $matchThese['name'] = $search_data['name'];
            }

            $product_name = null;
            $matchTheseN = [];
            if (!empty($search_data['product_name'])) {
                $option_ar[] = "Product Name";
                $search = true;
                $product_name = $search_data['product_name'];
                //  $matchTheseN['product_name'] = $request->get('product_name');
            }

            $product_type = null;
            if (!empty($search_data['product_type'])) {
                $option_ar[] = "Product Type";
                $search = true;
                $product_type = $search_data['product_type'];
                //  $matchThese['product_type'] = $request->get('product_type');
            }

            if (!empty($search_data['quantity'])) {
                $option_ar[] = "Quantity";
                $search = true;
                $matchThese['quantity'] = $search_data['quantity'];
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
        if ($search == true) {
            $stock_items = Stock::select('stock.*')->leftJoin("kitchen_items", "kitchen_items.id", '=', 'stock.product_id')
                ->where(function ($query) use ($matchThese, $product_name, $product_type) {
                    foreach ($matchThese as $key => $match) {
                        $query->where('stock.' . $key, 'like', "%$match%");
                    }

                    $query->where(function ($q) use ($product_type) {
                        $q->orWhereHas('group', function ($q) use ($product_type) {
                            $q->where('title', 'like', '%' . $product_type . '%');
                        })->orWhere('product_type', 'like', "%$product_type%");
                    });
                    /*$query->where(function ($query) use ($product_name, $product_type) {
                        $query->where('product_type', 'like', "%$product_type%")->orWhere('kitchen_items.name', 'like', "%$product_name%");
                    });*/
                })->orderBy('stock.created_at', 'DESC');
            $count = $stock_items->count();
            $stock_items = $stock_items->get();
            $matchThese['product_type'] = $request->get('product_type');
            $matchThese['product_name'] = $request->get('product_name');
        } else {
            $stock_items = Stock::select('stock.*');
            $stock_items = $stock_items->orderBy('created_at', "DESC");
            $count = $stock_items->count();
            if ($pagination == true) {
                $stock_items = $stock_items->paginate($per_page);
            } else {
                $stock_items = $stock_items->get();
            }
        }

        $all_ar[] = [
            'Item Name',
            'Product Type',
            'Product Name',
            'In stock Quantity',
        ];

        foreach ($stock_items as $stock)
        {
            $all_ar[] = [
                $stock->name,
                $stock->getGroup(),
                $stock->getProducts(),
                $stock->current_quantity.' '.$stock->quantity_units.'',
            ];
        }


        // Generate and return the spreadsheet
        $excel = \App::make('excel');
        $excel = $excel->create('Stock Items', function ($excel) use ($all_ar) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Stock Items');

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
            return $pdf->download('stock_items_list.pdf');
        }

        return redirect()->back()->with('error', 'Something went Wrong!!!');
    }

}
