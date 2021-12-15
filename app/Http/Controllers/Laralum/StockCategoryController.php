<?php

namespace App\Http\Controllers;

use App\StockCategory;
use Illuminate\Http\Request;

class StockCategoryCategoryController extends Controller
{
    //
    /**
     * stock listing
     * @return View
     */
    public function index(Request $request)
    {
        Laralum::permissionToAccess('stock');

        $matchThese =   [];
        $search = false;
        $option_ar = [];
        $stock_category = [];
        if ($request->has('filter_name') && $request->get('filter_name') != ""){
            $option_ar[] = "Name";
            $search = true;
            $matchThese['name'] = $request->get('filter_name');
        }

        $product_name = null;
        if ($request->has('filter_product_name') && $request->get('filter_product_name') != ""){
            $option_ar[] = "Product Name";
            $search = true;
            $product_name = $request->get('filter_product_name');
        }
        $options = implode(", ", $option_ar);

        $error = "Entered ".$options." is not valid,
make sure that you are entering valid ".$options." 
or search by other options";
        $user = [];
        $per_page = $request->get("per_page") ? $request->get("per_page") : 10;
        $pagination = true;
        if ($per_page == "All") {
            $pagination = false;
        }
        if ($search == true) {
            $stock_category = StockCategory::select('stock.*')->leftJoin("kitchen_items", "kitchen_items.id", '=', 'stock.product_id')
                ->where(function($query) use ($matchThese, $product_name) {
                    foreach($matchThese as $key=>$match){
                        $query->where('stock.'.$key,'like',"%$match%");
                    }
                    $query->where('product_type','like',"%$product_name%");
                });
            $count = $stock_category->count();
            if ($pagination == true) {
                $stock_category = $stock_category->paginate($per_page);
            }else{
                $stock_category = $stock_category->get();
            }

        }else{
            $stock_category = StockCategory::select('stock.*');
            $stock_category = $stock_category->orderBy('created_at', "DESC");
            $count = $stock_category->count();
            if ($pagination == true) {
                $stock_category = $stock_category->paginate($per_page);
            }else{
                $stock_category = $stock_category->get();
            }
        }



        return view('laralum.stock.index',compact('stock', 'error', 'count'));
    }

    /**
     * stock details with replies
     * @return View
     */
    public function view($id)
    {
        Laralum::permissionToAccess('stock');
        $stock_category = StockCategory::find($id);

        return view('laralum.stock.view',compact('stock'));
    }

    public function edit($id)
    {
        # Check permissions
        Laralum::permissionToAccess('stock');

        # Find the stock
        $row = StockCategory::findOrFail($id);
        \Session::put('stock_id', $id);

        # Get all the data
        $data_index = 'stock';
        require('Data/Edit/Get.php');

        # Return the view
        return view('laralum/stock/edit', [
            'row'       =>  $row,
            'fields'    =>  $fields,
            'confirmed' =>  $confirmed,
            'empty'     =>  $empty,
            'encrypted' =>  $encrypted,
            'hashed'    =>  $hashed,
            'masked'    =>  $masked,
            'table'     =>  $table,
            'code'      =>  $code,
            'wysiwyg'   =>  $wysiwyg,
            'relations' =>  $relations,
        ]);
    }

    public function update($id, Request $request)
    {
        # Check permissions
        Laralum::permissionToAccess('stock');

        # Find the row
        $stock_category = StockCategory::findOrFail($id);

        try {

            if ($stock_category->setData($request)) {
                $stock_category->save();
                return redirect()->route('Laralum::stock')->with('success', 'StockCategory edited successfully.');
            }else{
                return redirect()->route('Laralum::stock')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the stock, possible causes: ".$e->getMessage());
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
        Laralum::permissionToAccess('stock');

        # Get all the data
        $data_index = 'stock';
        require('Data/Create/Get.php');

        return view('laralum.stock.create',
            [
                'fields'        =>  $fields,
                'confirmed'     =>  $confirmed,
                'encrypted'     =>  $encrypted,
                'hashed'        =>  $hashed,
                'masked'        =>  $masked,
                'table'         =>  $table,
                'code'          =>  $code,
                'wysiwyg'       =>  $wysiwyg,
                'relations'     =>  $relations,
            ]);
    }

    public function store(Request $request)
    {
        Laralum::permissionToAccess('stock');
        $rules = StockCategory::getRules(true);

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(['error' => 'Please check the errors below and try again.']);
        }

        try {
            $stock_category = StockCategory::where('name', $request->get("name"))->first();
            if ($stock_category == null)
                $stock_category = new StockCategory();

            if ($stock_category->setData($request)) {
                $stock_category->save();
                return redirect()->route('Laralum::stock')->with('success', 'StockCategory added successfully.');
            }else{
                return redirect()->route('Laralum::stock')->with('error', 'Something went wrong. Please try again later.');
            }

        } catch (\Exception $e) {

            \Log::error("Failed to add the feedback question, possible causes: ".$e->getMessage());
            //print_r($e->getMessage());exit;
            return redirect()->route('Laralum::stock')->with('error', 'Something went wrong. Please try again later.');
        }

    }


    public function destroy($id)
    {   # Check permissions
        Laralum::permissionToAccess('stock');

        # Select StockCategory
        $stock_category = StockCategory::findOrFail($id);
        # Delete StockCategory
        $stock_category->delete();
        # Redirect the admin
        return redirect()->route('Laralum::stock')->with('success', trans('laralum.msg_stock_deleted'));

    }
}
